<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'name',
        'type',
        'account_number',
        'account_holder',
        'bank_name',
        'instructions',
        'icon',
        'is_active',
        'display_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'display_order' => 'integer',
    ];

    /**
     * Scope untuk mendapatkan metode pembayaran yang aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('display_order');
    }

    /**
     * Get display label based on type
     */
    public function getTypeLabel(): string
    {
        return match($this->type) {
            'bank' => 'Bank Transfer',
            'ewallet' => 'E-Wallet',
            default => ucfirst($this->type),
        };
    }

    /**
     * Get account type label
     */
    public function getAccountTypeLabel(): string
    {
        return match($this->type) {
            'bank' => 'Nomor Rekening',
            'ewallet' => 'Nomor HP/Akun',
            default => 'Nomor Akun',
        };
    }
}
