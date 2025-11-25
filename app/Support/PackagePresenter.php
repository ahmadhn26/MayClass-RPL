<?php

namespace App\Support;

use App\Models\Package;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class PackagePresenter
{
    private const DEFAULT_STAGE_ORDER = ['SD', 'SMP', 'SMA'];

    public static function card(Package $package): array
    {
        $data = self::base($package);

        $data['features'] = $package->relationLoaded('cardFeatures')
            ? $package->cardFeatures->sortBy('position')->pluck('label')->values()->all()
            : [];

        $data['card_features'] = $data['features'];

        return $data;
    }

    public static function detail(Package $package): array
    {
        $data = self::card($package);

        $data['included'] = $package->relationLoaded('inclusions')
            ? $package->inclusions->sortBy('position')->pluck('label')->values()->all()
            : [];

        return $data;
    }

    public static function groupByStage(Collection $packages): Collection
    {
        $sorted = $packages
            ->sortBy(fn (Package $package) => self::stagePriority($package->level))
            ->values();

        return $sorted->groupBy('level')->map(function (Collection $group, string $stage) {
            return [
                'stage' => $stage,
                'stage_label' => self::stageLabel($stage),
                'stage_description' => self::stageDescription($stage),
                'packages' => $group->map(fn (Package $package) => self::card($package))->values()->all(),
            ];
        })->values();
    }

    public static function stageLabel(?string $stage): string
    {
        $definition = self::stageDefinition($stage);

        if (is_array($definition)) {
            return $definition['label'] ?? ($stage ?? '');
        }

        if (is_string($definition)) {
            return $definition;
        }

        return $stage ?? '';
    }

    public static function stageDescription(?string $stage): string
    {
        $definition = self::stageDefinition($stage);

        if (is_array($definition)) {
            return $definition['description'] ?? '';
        }

        return '';
    }

    private static function base(Package $package): array
    {
        $quota = method_exists($package, 'quotaSnapshot')
            ? $package->quotaSnapshot()
            : ['limit' => null, 'remaining' => null, 'used' => 0, 'active_enrollments' => 0, 'checkout_holds' => 0, 'is_full' => false];

        return [
            'id' => $package->id,
            'slug' => $package->slug,
            'stage' => $package->level,
            'level' => $package->level,
            'stage_label' => self::stageLabel($package->level),
            'grade_range' => $package->grade_range,
            'tag' => $package->tag,
            'title' => $package->detail_title,
            'card_price' => $package->card_price_label,
            'detail_title' => $package->detail_title,
            'detail_price' => $package->detail_price_label,
            'summary' => $package->summary,
            'image' => $package->image_asset,
            'price_numeric' => (int) round($package->price),
            'quota_limit' => $quota['limit'],
            'quota_remaining' => $quota['remaining'],
            'quota_used' => $quota['used'],
            'quota_active_enrollments' => $quota['active_enrollments'],
            'quota_checkout_holds' => $quota['checkout_holds'],
            'is_full' => $quota['is_full'],
        ];
    }

    private static function stagePriority(?string $stage): int
    {
        $order = collect(config('mayclass.package_stages'))
            ->keys()
            ->values()
            ->all();

        $order = ! empty($order) ? $order : self::DEFAULT_STAGE_ORDER;
        $priorityMap = array_flip($order);

        return $priorityMap[$stage] ?? (count($priorityMap) + 1);
    }

    private static function stageDefinition(?string $stage)
    {
        if ($stage === null) {
            return null;
        }

        $stages = config('mayclass.package_stages');

        if (! is_array($stages)) {
            return null;
        }

        return Arr::get($stages, $stage);
    }
}
