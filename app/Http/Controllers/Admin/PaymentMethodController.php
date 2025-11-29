<?php

namespace App\Http\Controllers\Admin;

use App\Models\PaymentMethod;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PaymentMethodController extends BaseAdminController
{
    public function index()
    {
        $methods = PaymentMethod::orderBy('display_order')->orderBy('created_at')->get();
        
        return $this->render('admin.payment-methods.index', [
            'methods' => $methods,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'slug' => ['required', 'string', 'alpha_dash', 'max:50', 'unique:payment_methods,slug'],
            'name' => ['required', 'string', 'max:100'],
            'type' => ['required', Rule::in(['bank', 'ewallet'])],
            'account_number' => ['required', 'string', 'max:100'],
            'account_holder' => ['required', 'string', 'max:255'],
            'bank_name' => ['nullable', 'required_if:type,bank', 'string', 'max:100'],
            'instructions' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
            'display_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $data['is_active'] = $request->has('is_active') ? (bool) $request->is_active : true;
        $data['display_order'] = $data['display_order'] ?? 999;

        PaymentMethod::create($data);

        return redirect()
            ->route('admin.payment-methods.index')
            ->with('status', 'Metode pembayaran berhasil ditambahkan.');
    }

    public function update(Request $request, PaymentMethod $paymentMethod): RedirectResponse
    {
        $data = $request->validate([
            'slug' => ['required', 'string', 'alpha_dash', 'max:50', Rule::unique('payment_methods', 'slug')->ignore($paymentMethod->id)],
            'name' => ['required', 'string', 'max:100'],
            'type' => ['required', Rule::in(['bank', 'ewallet'])],
            'account_number' => ['required', 'string', 'max:100'],
            'account_holder' => ['required', 'string', 'max:255'],
            'bank_name' => ['nullable', 'required_if:type,bank', 'string', 'max:100'],
            'instructions' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
            'display_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $data['is_active'] = $request->has('is_active') ? (bool) $request->is_active : $paymentMethod->is_active;
        $data['display_order'] = $data['display_order'] ?? $paymentMethod->display_order;

        $paymentMethod->update($data);

        return redirect()
            ->route('admin.payment-methods.index')
            ->with('status', 'Metode pembayaran berhasil diperbarui.');
    }

    public function destroy(PaymentMethod $paymentMethod): RedirectResponse
    {
        $paymentMethod->delete();

        return redirect()
            ->route('admin.payment-methods.index')
            ->with('status', 'Metode pembayaran berhasil dihapus.');
    }
}
