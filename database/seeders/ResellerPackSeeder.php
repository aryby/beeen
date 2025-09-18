<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ResellerPack;

class ResellerPackSeeder extends Seeder
{
    public function run()
    {
        $packs = [
            [
                'name' => 'Pack Starter',
                'description' => 'Pack de démarrage pour nouveaux revendeurs',
                'credits' => 10,
                'price' => 99.99,
                'is_active' => true,
            ],
            [
                'name' => 'Pack Business',
                'description' => 'Pack pour revendeurs actifs',
                'credits' => 50,
                'price' => 399.99,
                'is_active' => true,
            ],
            [
                'name' => 'Pack Pro',
                'description' => 'Pack pour revendeurs professionnels',
                'credits' => 100,
                'price' => 699.99,
                'is_active' => true,
            ],
            [
                'name' => 'Pack Enterprise',
                'description' => 'Pack pour grosses entreprises',
                'credits' => 500,
                'price' => 2999.99,
                'is_active' => true,
            ],
        ];

        foreach ($packs as $packData) {
            ResellerPack::firstOrCreate(
                ['name' => $packData['name']],
                $packData
            );
        }
    }
}
