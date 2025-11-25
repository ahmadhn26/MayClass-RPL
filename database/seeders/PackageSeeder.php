<?php

namespace Database\Seeders;

use App\Models\Enrollment;
use App\Models\Order;
use App\Models\Package;
use App\Models\PackageFeature;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class PackageSeeder extends Seeder
{
    public function run(): void
    {
        if (!Schema::hasTable('packages')) {
            return;
        }

        if (Schema::hasTable('enrollments')) {
            Enrollment::query()->delete();
        }

        if (Schema::hasTable('orders')) {
            Order::query()->delete();
        }

        $featureTableAvailable = Schema::hasTable('package_features');

        if ($featureTableAvailable) {
            PackageFeature::query()->delete();
        }

        Package::query()->delete();

        $packages = [
            // Demo packages removed as per user request
        ];

        foreach ($packages as $packageData) {
            $package = Package::create([
                'slug' => $packageData['slug'],
                'level' => $packageData['level'],
                'grade_range' => $packageData['grade_range'],
                'card_price_label' => $packageData['card_price_label'],
                'detail_title' => $packageData['detail_title'],
                'detail_price_label' => $packageData['detail_price_label'],
                'image_url' => $packageData['image_url'],
                'price' => $packageData['price'],
                'summary' => $packageData['summary'],
            ]);

            if ($featureTableAvailable) {
                $this->seedFeatures($package, 'card', $packageData['card_features']);
                $this->seedFeatures($package, 'included', $packageData['inclusions']);
            }
        }
    }

    private function seedFeatures(Package $package, string $type, array $labels): void
    {
        foreach (array_values($labels) as $index => $label) {
            PackageFeature::create([
                'package_id' => $package->id,
                'type' => $type,
                'label' => $label,
                'position' => $index + 1,
            ]);
        }
    }
}
