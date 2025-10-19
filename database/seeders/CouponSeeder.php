<?php

// database/seeders/CouponSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Coupon;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        $coupons = [
            [
                'code' => 'WELCOME10',
                'description' => 'Descuento de bienvenida del 10%',
                'type' => 'percentage',
                'value' => 10,
                'min_purchase' => 20,
                'usage_limit' => 100,
                'usage_per_user' => 1,
                'is_active' => true,
                'starts_at' => now(),
                'expires_at' => now()->addMonths(3),
            ],
            [
                'code' => 'SAVE5',
                'description' => '5€ de descuento en compras superiores a 30€',
                'type' => 'fixed',
                'value' => 5,
                'min_purchase' => 30,
                'usage_limit' => 50,
                'usage_per_user' => 2,
                'is_active' => true,
                'starts_at' => now(),
                'expires_at' => now()->addMonth(),
            ],
            [
                'code' => 'PREMIUM20',
                'description' => '20% de descuento para clientes premium',
                'type' => 'percentage',
                'value' => 20,
                'min_purchase' => 50,
                'usage_limit' => null,
                'usage_per_user' => null,
                'is_active' => true,
                'starts_at' => now(),
                'expires_at' => null,
            ],
            [
                'code' => 'EXPIRED',
                'description' => 'Cupón expirado (para testing)',
                'type' => 'percentage',
                'value' => 50,
                'min_purchase' => null,
                'usage_limit' => 10,
                'usage_per_user' => 1,
                'is_active' => true,
                'starts_at' => now()->subMonths(2),
                'expires_at' => now()->subMonth(),
            ],
        ];

        foreach ($coupons as $coupon) {
            Coupon::create($coupon);
        }
    }
}