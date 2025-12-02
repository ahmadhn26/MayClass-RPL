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
            'name' => ['required', 'string', 'max:100'],
            'type' => ['required', Rule::in(['bank', 'ewallet'])],
            'account_number' => ['required', 'string', 'max:100'],
            'account_holder' => ['required', 'string', 'max:255'],
            'bank_name' => ['nullable', 'required_if:type,bank', 'string', 'max:100'],
            'instructions' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        // Auto-generate slug from name
        $baseSlug = \Illuminate\Support\Str::slug($data['name']);
        $slug = $baseSlug;
        $counter = 1;
        
        // Handle duplicate slugs by adding suffix
        while (PaymentMethod::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }
        $data['slug'] = $slug;

        // Auto-generate display_order
        $data['display_order'] = (PaymentMethod::max('display_order') ?? 0) + 1;

        $data['is_active'] = $request->has('is_active') ? (bool) $request->is_active : true;

        PaymentMethod::create($data);

        return redirect()
            ->route('admin.payment-methods.index')
            ->with('status', 'Metode pembayaran berhasil ditambahkan.');
    }

    public function update(Request $request, PaymentMethod $paymentMethod): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'type' => ['required', Rule::in(['bank', 'ewallet'])],
            'account_number' => ['required', 'string', 'max:100'],
            'account_holder' => ['required', 'string', 'max:255'],
            'bank_name' => ['nullable', 'required_if:type,bank', 'string', 'max:100'],
            'instructions' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $data['is_active'] = $request->has('is_active') ? (bool) $request->is_active : $paymentMethod->is_active;
        
        // Slug and display_order are immutable - keep existing values
        // (not included in $data, so they won't be updated)

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
