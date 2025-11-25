<?php

namespace App\Http\Controllers;

use App\Exceptions\PackageFullException;
use App\Models\Order;
use App\Models\Package;
use App\Models\CheckoutSession;
use App\Support\PackagePresenter;
use App\Support\ProfileAvatar;
use App\Support\ProfileLinkResolver;
use App\Support\StudentAccess;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CheckoutController extends Controller
{
    public function show(Request $request, string $slug)
    {
        $package = Package::with(['cardFeatures', 'inclusions'])->withQuotaUsage()->where('slug', $slug)->firstOrFail();
        $user = $request->user();

        if ($redirect = $this->redirectIfPurchaseLocked($user)) {
            return $redirect;
        }

        $packageDetail = $this->formatPackage($package);
        $existingOrder = $this->latestSubmittedOrder($user->id, $package->id);

        if ($existingOrder) {
            $this->syncSessionForOrder($existingOrder, $package);

            if ($existingOrder->status === 'paid') {
                return $this->redirectToStudentDashboard($packageDetail);
            }

            return redirect()->route('checkout.success', ['slug' => $package->slug, 'order' => $existingOrder->id]);
        }

        try {
            [$order] = $this->ensureCheckoutSession($user->id, $package);
        } catch (PackageFullException $exception) {
            return redirect()
                ->route('packages.show', $package->slug)
                ->with('package_full', $exception->getMessage());
        }

        if (! $order->expires_at) {
            $order->forceFill(['expires_at' => now()->addMinutes(30)])->save();
            $order->refresh();
        }

        $expiresAt = $order->expires_at ?? now()->addMinutes(30);
        $remainingSeconds = max(0, now()->diffInSeconds($expiresAt, false));

        return view('checkout.index', [
            'package' => $packageDetail,
            'order' => $order,
            'countdownSeconds' => $remainingSeconds,
            'financeWhatsappLink' => $this->buildFinanceWhatsappLink($packageDetail),
            'profileLink' => ProfileLinkResolver::forUser($user),
            'profileAvatar' => ProfileAvatar::forUser($user),
        ]);
    }

    public function store(Request $request, string $slug): RedirectResponse
    {
        if ($redirect = $this->redirectIfPurchaseLocked($request->user())) {
            return $redirect;
        }

        $package = Package::withQuotaUsage()->where('slug', $slug)->firstOrFail();

        $data = $request->validate([
            'payment_method' => ['required', Rule::in(['american_express', 'visa', 'transfer_bank'])],
            'cardholder_name' => ['required', 'string', 'max:255'],
            'card_number' => ['required', 'string', 'max:25'],
            'payment_proof' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'order_id' => ['required', Rule::exists('orders', 'id')->where(fn ($query) => $query->where('user_id', $request->user()->id)->where('status', 'initiated'))],
        ]);

        $user = Auth::user();

        $order = Order::where('id', $data['order_id'])
            ->where('user_id', $user->id)
            ->where('package_id', $package->id)
            ->where('status', 'initiated')
            ->firstOrFail();

        if ($order->expires_at && $order->expires_at->isPast()) {
            $order->forceFill([
                'status' => 'failed',
                'cancelled_at' => now(),
            ])->save();

            $this->updateSessionStatus($order, $package, 'expired');

            return redirect()
                ->route('checkout.show', $package->slug)
                ->with('checkout_expired', true);
        }

        $proofPath = $request->file('payment_proof')->store('payment-proofs', 'public');

        if ($order->payment_proof_path) {
            Storage::disk('public')->delete($order->payment_proof_path);
        }

        $order->forceFill([
            'status' => 'pending',
            'payment_method' => $data['payment_method'],
            'cardholder_name' => $data['cardholder_name'],
            'card_number_last_four' => substr(preg_replace('/\D/', '', $data['card_number']), -4),
            'payment_proof_path' => $proofPath,
            'expires_at' => null,
            'cancelled_at' => null,
        ])->save();

        $this->updateSessionStatus($order, $package, 'awaiting_verification');

        return redirect()->route('checkout.success', ['slug' => $package->slug, 'order' => $order->id]);
    }

    public function success(Request $request, string $slug)
    {
        $package = Package::with(['cardFeatures', 'inclusions'])->where('slug', $slug)->firstOrFail();
        $packageDetail = $this->formatPackage($package);
        $orderId = $request->query('order');

        $orderQuery = Order::with('user')
            ->where('package_id', $package->id)
            ->where('user_id', Auth::id());

        if ($orderId) {
            $order = $orderQuery->where('id', $orderId)->first();
        } else {
            $order = $orderQuery
                ->whereIn('status', ['pending', 'paid'])
                ->latest('updated_at')
                ->first();
        }

        if (! $order) {
            return redirect()->route('checkout.show', $slug);
        }

        $activationRequest = $request->boolean('activated');

        $this->syncSessionForOrder($order, $package);

        if ($order->status === 'paid' && ! $activationRequest) {
            return $this->redirectToStudentDashboard($packageDetail);
        }

        if ($order->status !== 'pending' && ! ($order->status === 'paid' && $activationRequest)) {
            return redirect()->route('checkout.show', $slug);
        }

        return view('checkout.success', [
            'package' => $packageDetail,
            'order' => $order,
            'statusCheckUrl' => $order->status === 'pending'
                ? route('checkout.status', ['slug' => $package->slug, 'order' => $order->id])
                : null,
            'profileLink' => ProfileLinkResolver::forUser($request->user()),
            'profileAvatar' => ProfileAvatar::forUser($request->user()),
            'showActivationModal' => $order->status === 'paid' && $activationRequest,
            'activationRedirectUrl' => route('student.dashboard'),
        ]);
    }

    public function status(Request $request, string $slug, Order $order): JsonResponse
    {
        abort_unless($order->user_id === $request->user()->id, 403);

        $order->loadMissing('package');

        if ($order->package?->slug !== $slug) {
            abort(404);
        }

        return response()->json([
            'status' => $order->status,
            'should_redirect' => $order->status === 'paid',
            'updated_at' => optional($order->updated_at)->toIso8601String(),
        ]);
    }

    public function expire(Request $request, string $slug, Order $order): JsonResponse
    {
        abort_unless($order->user_id === $request->user()->id, 403);
        abort_unless($order->package?->slug === $slug, 404);

        $order->loadMissing('package');

        if ($order->status === 'initiated') {
            $order->forceFill([
                'status' => 'failed',
                'cancelled_at' => now(),
            ])->save();

            $this->updateSessionStatus($order, $order->package, 'expired');
        }

        return response()->json(['status' => 'expired']);
    }

    private function formatPackage(Package $package): array
    {
        return PackagePresenter::detail($package);
    }

    private function buildFinanceWhatsappLink(array $packageDetail): string
    {
        $whatsappNumber = config('services.whatsapp.finance_admin', '6281234567890');
        $packageName = $packageDetail['detail_title'] ?? $packageDetail['title'] ?? 'MayClass';
        $message = rawurlencode('Halo Admin Keuangan MayClass, saya butuh bantuan pembayaran untuk paket ' . $packageName);

        return "https://wa.me/{$whatsappNumber}?text={$message}";
    }

    private function redirectToStudentDashboard(array $packageDetail): RedirectResponse
    {
        $targetRoute = Auth::user()?->role === 'student'
            ? 'student.dashboard'
            : 'packages.index';

        return redirect()
            ->route($targetRoute)
            ->with('subscription_success', $this->buildActivationMessage($packageDetail));
    }

    private function buildActivationMessage(array $packageDetail): string
    {
        $title = $packageDetail['detail_title'] ?? $packageDetail['title'] ?? 'paket MayClass';

        return 'Pembayaran ' . $title . ' sudah diverifikasi. Kamu bisa langsung mengakses materi dari dashboard siswa.';
    }

    private function ensureCheckoutSession(int $userId, Package $package): array
    {
        return DB::transaction(function () use ($userId, $package) {
            $lockedPackage = Package::withQuotaUsage()->whereKey($package->id)->lockForUpdate()->firstOrFail();

            $activeSession = CheckoutSession::where('user_id', $userId)
                ->where('package_id', $lockedPackage->id)
                ->whereIn('status', ['checkout_in_progress', 'awaiting_payment', 'awaiting_verification'])
                ->latest('id')
                ->lockForUpdate()
                ->first();

            if ($activeSession) {
                $order = $this->resolveDraftOrder($userId, $lockedPackage, lock: true);

                if (! $activeSession->order_id) {
                    $activeSession->forceFill(['order_id' => $order->id])->save();
                }

                return [$order->fresh(), $activeSession->fresh()];
            }

            $this->assertPackageSlot($lockedPackage);

            $order = $this->resolveDraftOrder($userId, $lockedPackage, lock: true);

            $session = CheckoutSession::create([
                'user_id' => $userId,
                'package_id' => $lockedPackage->id,
                'order_id' => $order->id,
                'status' => 'checkout_in_progress',
                'context' => [
                    'package_slug' => $lockedPackage->slug,
                    'package_title' => $lockedPackage->detail_title ?? $lockedPackage->title ?? $lockedPackage->name,
                ],
            ]);

            return [$order->fresh(), $session];
        });
    }

    private function syncSessionForOrder(Order $order, Package $package): CheckoutSession
    {
        $session = CheckoutSession::firstOrNew(['order_id' => $order->id]);

        if (! $session->exists) {
            $session->forceFill([
                'user_id' => $order->user_id,
                'package_id' => $order->package_id,
                'context' => [
                    'package_slug' => $package->slug,
                    'package_title' => $package->detail_title ?? $package->title ?? $package->name,
                ],
            ]);
        }

        $targetStatus = $order->status === 'paid' ? 'completed' : 'awaiting_verification';

        if ($session->status !== $targetStatus) {
            $session->status = $targetStatus;
        }

        if (! $session->order_id) {
            $session->order_id = $order->id;
        }

        $session->save();

        return $session;
    }

    private function updateSessionStatus(Order $order, Package $package, string $status): void
    {
        $session = CheckoutSession::where('order_id', $order->id)
            ->latest('id')
            ->first();

        if (! $session) {
            $session = CheckoutSession::create([
                'user_id' => $order->user_id,
                'package_id' => $package->id,
                'order_id' => $order->id,
                'status' => $status,
                'context' => [
                    'package_slug' => $package->slug,
                    'package_title' => $package->detail_title ?? $package->title ?? $package->name,
                ],
            ]);

            return;
        }

        if (! $session->order_id) {
            $session->order_id = $order->id;
        }

        $session->status = $status;
        $session->save();
    }

    private function resolveDraftOrder(int $userId, Package $package, bool $lock = false): Order
    {
        $subtotal = $package->price;
        $tax = round($subtotal * 0.11, 2);
        $total = $subtotal + $tax;
        $expiresAt = now()->addMinutes(30);

        $draftQuery = Order::where('user_id', $userId)
            ->where('package_id', $package->id)
            ->where('status', 'initiated')
            ->latest('id');

        if ($lock) {
            $draftQuery->lockForUpdate();
        }

        $draft = $draftQuery->first();

        if ($draft && $draft->expires_at && $draft->expires_at->isPast()) {
            $draft->forceFill([
                'status' => 'failed',
                'cancelled_at' => now(),
            ])->save();
            $draft = null;
        }

        if ($draft) {
            $draft->forceFill([
                'subtotal' => $subtotal,
                'tax' => $tax,
                'total' => $total,
                'expires_at' => $expiresAt,
            ])->save();

            return $draft->fresh();
        }

        return Order::create([
            'user_id' => $userId,
            'package_id' => $package->id,
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
            'status' => 'initiated',
            'payment_method' => 'transfer_bank',
            'expires_at' => $expiresAt,
        ]);
    }

    private function assertPackageSlot(Package $package): void
    {
        if (! $package->hasQuota()) {
            return;
        }

        $snapshot = $package->quotaSnapshot(lock: true);

        if ($snapshot['remaining'] !== null && $snapshot['remaining'] <= 0) {
            throw new PackageFullException('Kuota paket ini sudah habis. Silakan pilih paket lain atau hubungi admin.');
        }
    }

    private function latestSubmittedOrder(int $userId, int $packageId): ?Order
    {
        return Order::where('user_id', $userId)
            ->where('package_id', $packageId)
            ->whereIn('status', ['pending', 'paid'])
            ->whereNotNull('payment_proof_path')
            ->latest('updated_at')
            ->first();
    }

    private function redirectIfPurchaseLocked($user): ?RedirectResponse
    {
        if (! $user || $user->role !== 'student') {
            return null;
        }

        if (! StudentAccess::hasActivePackage($user)) {
            return null;
        }

        $enrollment = StudentAccess::activeEnrollment($user);
        $package = optional($enrollment)->package;

        if (! $package) {
            return null;
        }

        $packageName = $package->detail_title
            ?? $package->title
            ?? $package->name
            ?? 'paket MayClass';

        return redirect()
            ->route('student.dashboard')
            ->with('purchase_locked', 'Kamu sudah aktif di ' . $packageName . '. Paket baru dapat dibeli setelah paket ini selesai atau dinonaktifkan.');
    }
}
