<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Package;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class DemoOrderSeeder extends Seeder
{
    public function run(): void
    {
        if (! Schema::hasTable('orders') || ! Schema::hasTable('packages') || ! Schema::hasTable('users')) {
            return;
        }

        $students = User::where('role', 'student')->get()->keyBy('email');
        $packages = Package::all()->keyBy('slug');

        if ($students->isEmpty() || $packages->isEmpty()) {
            return;
        }

        $proofPath = 'payment-proofs/demo-proof.txt';
        if (! Storage::disk('public')->exists($proofPath)) {
            Storage::disk('public')->put($proofPath, 'Demo proof for pending verification.');
        }

        $entries = [
            [
                'email' => 'nadia@student.mayclass.test',
                'package' => 'mayclass-sd-fundamental',
                'status' => 'paid',
                'created_at' => now()->subMonths(2)->subDays(2),
                'paid_at' => now()->subMonths(2)->subDay(),
                'has_proof' => true,
            ],
            [
                'email' => 'farhan@student.mayclass.test',
                'package' => 'mayclass-smp-eksplor',
                'status' => 'paid',
                'created_at' => now()->subMonth()->subDays(10),
                'paid_at' => now()->subMonth()->subDays(9),
                'has_proof' => true,
            ],
            [
                'email' => 'laras@student.mayclass.test',
                'package' => 'mayclass-sma-premium',
                'status' => 'pending',
                'created_at' => now()->subDays(6),
                'paid_at' => null,
                'has_proof' => true,
            ],
            [
                'email' => 'rafi@student.mayclass.test',
                'package' => 'mayclass-sd-unggul',
                'status' => 'failed',
                'created_at' => now()->subDays(12),
                'paid_at' => null,
                'has_proof' => false,
            ],
            [
                'email' => 'salsa@student.mayclass.test',
                'package' => 'mayclass-sma-premium',
                'status' => 'pending',
                'created_at' => now()->subDays(1),
                'paid_at' => null,
                'has_proof' => true,
            ],
            [
                'email' => 'indra@student.mayclass.test',
                'package' => 'mayclass-smp-unggul',
                'status' => 'initiated',
                'created_at' => now()->subHours(2),
                'paid_at' => null,
                'has_proof' => false,
                'expires_at' => now()->addMinutes(25),
            ],
        ];

        foreach ($entries as $entry) {
            $student = $students->get($entry['email']) ?? $students->first();
            $package = $packages->get($entry['package']) ?? $packages->first();

            if (! $student || ! $package) {
                continue;
            }

            $subtotal = $package->price;
            $tax = round($subtotal * 0.11, 2);
            $total = $subtotal + $tax;

            $order = Order::create([
                'user_id' => $student->id,
                'package_id' => $package->id,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'total' => $total,
                'status' => $entry['status'],
                'payment_method' => 'transfer_bank',
                'cardholder_name' => $student->name,
                'card_number_last_four' => '4321',
                'payment_proof_path' => $entry['has_proof'] ? $proofPath : null,
                'paid_at' => $entry['paid_at'],
                'cancelled_at' => $entry['status'] === 'failed' ? $entry['created_at']->addHours(3) : null,
                'expires_at' => $entry['expires_at'] ?? null,
            ]);

            $order->forceFill([
                'created_at' => $entry['created_at'],
                'updated_at' => $entry['created_at'],
            ])->save();
        }
    }
}
