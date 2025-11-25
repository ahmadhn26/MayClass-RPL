<?php

namespace App\Models;

use App\Support\ImageRepository;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'level',
        'grade_range',
        'tag',
        'card_price_label',
        'detail_title',
        'detail_price_label',
        'image_url',
        'price',
        'max_students',
        'summary',
    ];

    protected $casts = [
        'price' => 'float',
        'max_students' => 'integer',
    ];

    public function getImageAssetAttribute(): string
    {
        $key = $this->attributes['image_url'] ?? '';

        return ImageRepository::url("packages.$key");
    }

    public function features(): HasMany
    {
        return $this->hasMany(PackageFeature::class);
    }

    public function cardFeatures(): HasMany
    {
        return $this->features()->where('type', 'card')->orderBy('position');
    }

    public function tutors(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->where('role', 'tutor')->withTimestamps();
    }

    public function inclusions(): HasMany
    {
        return $this->features()->where('type', 'included')->orderBy('position');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function checkoutSessions(): HasMany
    {
        return $this->hasMany(CheckoutSession::class);
    }

    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class)->withTimestamps();
    }

    public function scopeWithQuotaUsage($query)
    {
        return $query
            ->withCount([
                'enrollments as active_enrollment_count' => function ($enrollments) {
                    $enrollments
                        ->where('is_active', true)
                        ->where(function ($query) {
                            $query
                                ->whereNull('ends_at')
                                ->orWhere('ends_at', '>=', now());
                        });
                },
                'checkoutSessions as pending_checkout_count' => function ($sessions) {
                    $sessions
                        ->whereIn('status', ['checkout_in_progress', 'awaiting_payment', 'awaiting_verification'])
                        ->where(function ($query) {
                            $query
                                ->whereNull('order_id')
                                ->orWhereHas('order', function ($orders) {
                                    $orders
                                        ->whereIn('status', ['initiated', 'pending'])
                                        ->where(function ($orderQuery) {
                                            $orderQuery
                                                ->whereNull('expires_at')
                                                ->orWhere('expires_at', '>', now());
                                        });
                                });
                        });
                },
            ]);
    }

    public function quotaSnapshot(bool $lock = false): array
    {
        $limit = $this->max_students !== null ? (int) $this->max_students : null;
        $activeEnrollments = $this->resolvedActiveEnrollmentCount($lock);
        $checkoutHolds = $this->resolvedPendingCheckoutCount($lock);
        $used = $activeEnrollments + $checkoutHolds;
        $remaining = $limit !== null ? max(0, $limit - $used) : null;

        return [
            'limit' => $limit,
            'active_enrollments' => $activeEnrollments,
            'checkout_holds' => $checkoutHolds,
            'used' => $used,
            'remaining' => $remaining,
            'is_full' => $limit !== null && $remaining <= 0,
        ];
    }

    public function hasQuota(): bool
    {
        return $this->max_students !== null;
    }

    private function resolvedActiveEnrollmentCount(bool $lock = false): int
    {
        $query = $this->enrollments()
            ->where('is_active', true)
            ->where(function ($subQuery) {
                $subQuery
                    ->whereNull('ends_at')
                    ->orWhere('ends_at', '>=', now());
            });

        if ($lock) {
            $query->lockForUpdate();
        }

        if (! $lock && property_exists($this, 'active_enrollment_count')) {
            return (int) $this->active_enrollment_count;
        }

        return (int) $query->count();
    }

    private function resolvedPendingCheckoutCount(bool $lock = false): int
    {
        $query = $this->checkoutSessions()
            ->whereIn('status', ['checkout_in_progress', 'awaiting_payment', 'awaiting_verification'])
            ->where(function ($sessionQuery) {
                $sessionQuery
                    ->whereNull('order_id')
                    ->orWhereHas('order', function ($orders) {
                        $orders
                            ->whereIn('status', ['initiated', 'pending'])
                            ->where(function ($orderQuery) {
                                $orderQuery
                                    ->whereNull('expires_at')
                                    ->orWhere('expires_at', '>', now());
                            });
                    });
            });

        if ($lock) {
            $query->lockForUpdate();
        }

        if (! $lock && property_exists($this, 'pending_checkout_count')) {
            return (int) $this->pending_checkout_count;
        }

        return (int) $query->count();
    }
}
