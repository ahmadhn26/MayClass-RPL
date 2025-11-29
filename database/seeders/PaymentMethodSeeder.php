<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $methods = [
            [
                'slug' => 'transfer_bank',
                'name' => 'Transfer Bank',
                'type' => 'bank',
                'bank_name' => 'BCA',
                'account_number' => '1234567890',
                'account_holder' => 'Maylina',
                'instructions' => 'Transfer ke rekening BCA dan upload bukti transfer.',
                'is_active' => true,
                'display_order' => 1,
            ],
            [
                'slug' => 'shopeepay',
                'name' => 'ShopeePay',
                'type' => 'ewallet',
                'account_number' => '081234567890',
                'account_holder' => 'Maylina',
                'instructions' => 'Transfer ke nomor ShopeePay dan upload bukti transfer.',
                'is_active' => true,
                'display_order' => 2,
            ],
            [
                'slug' => 'gopay',
                'name' => 'GoPay',
                'type' => 'ewallet',
                'account_number' => '081234567890',
                'account_holder' => 'Maylina',
                'instructions' => 'Transfer ke nomor GoPay dan upload bukti transfer.',
                'is_active' => true,
                'display_order' => 3,
            ],
            [
                'slug' => 'ovo',
                'name' => 'OVO',
                'type' => 'ewallet',
                'account_number' => '081234567890',
                'account_holder' => 'Maylina',
                'instructions' => 'Transfer ke nomor OVO dan upload bukti transfer.',
                'is_active' => true,
                'display_order' => 4,
            ],
            [
                'slug' => 'dana',
                'name' => 'DANA',
                'type' => 'ewallet',
                'account_number' => '081234567890',
                'account_holder' => 'Maylina',
                'instructions' => 'Transfer ke nomor DANA dan upload bukti transfer.',
                'is_active' => true,
                'display_order' => 5,
            ],
        ];

        foreach ($methods as $method) {
            PaymentMethod::updateOrCreate(
                ['slug' => $method['slug']],
                $method
            );
        }
    }
}
