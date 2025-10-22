<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            ['name' => 'featured', 'description' => 'Produk unggulan yang ingin disorot di etalase.'],
            ['name' => 'clearance', 'description' => 'Produk dengan stok menipis atau diskon akhir.'],
            ['name' => 'digital', 'description' => 'Produk digital tanpa pengiriman fisik.'],
            ['name' => 'bundle', 'description' => 'Produk paket yang terdiri dari beberapa item.'],
        ];

        foreach ($tags as $tag) {
            Tag::updateOrCreate(
                ['name' => $tag['name']],
                ['description' => $tag['description']]
            );
        }
    }
}
