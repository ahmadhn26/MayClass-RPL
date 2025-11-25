<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Support\PackagePresenter;
use App\Support\ProfileAvatar;
use App\Support\ProfileLinkResolver;
use App\Support\StudentAccess;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class PackageController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $activeEnrollment = StudentAccess::activeEnrollment($user);
        $hasActivePackage = StudentAccess::hasActivePackage($user);

        if (! Schema::hasTable('packages')) {
            return view('packages.index', [
                'catalog' => collect(),
                'stageDefinitions' => config('mayclass.package_stages', []),
                'profileLink' => ProfileLinkResolver::forUser($user),
                'profileAvatar' => ProfileAvatar::forUser($user),
                'studentHasActivePackage' => $hasActivePackage,
                'studentActivePackageName' => $this->resolveActivePackageName($activeEnrollment),
            ]);
        }

        $query = Package::query()->withQuotaUsage()->orderBy('level')->orderBy('price');

        if (Schema::hasTable('package_features')) {
            $query->with(['cardFeatures' => fn ($features) => $features->orderBy('position')]);
        }

        $packages = $query->get();
        $catalog = PackagePresenter::groupByStage($packages);

        return view('packages.index', [
            'catalog' => $catalog,
            'stageDefinitions' => config('mayclass.package_stages', []),
            'profileLink' => ProfileLinkResolver::forUser($user),
            'profileAvatar' => ProfileAvatar::forUser($user),
            'studentHasActivePackage' => $hasActivePackage,
            'studentActivePackageName' => $this->resolveActivePackageName($activeEnrollment),
        ]);
    }

    public function show(string $slug)
    {
        $package = Package::with(['cardFeatures', 'inclusions', 'subjects'])->withQuotaUsage()->where('slug', $slug)->firstOrFail();
        $user = Auth::user();
        $activeEnrollment = StudentAccess::activeEnrollment($user);

        return view('packages.show', [
            'package' => $this->formatPackage($package),
            'subjects' => $package->subjects,
            'profileLink' => ProfileLinkResolver::forUser($user),
            'profileAvatar' => ProfileAvatar::forUser($user),
            'studentHasActivePackage' => StudentAccess::hasActivePackage($user),
            'studentActivePackageName' => $this->resolveActivePackageName($activeEnrollment),
        ]);
    }

    private function formatPackage(Package $package): array
    {
        return PackagePresenter::detail($package);
    }

    private function resolveActivePackageName($enrollment): ?string
    {
        if (! $enrollment || ! $enrollment->package) {
            return null;
        }

        $package = $enrollment->package;

        return $package->detail_title
            ?? $package->title
            ?? $package->name
            ?? $package->slug
            ?? null;
    }
}
