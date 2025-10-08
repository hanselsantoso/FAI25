<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Product, Category, Supplier};

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all();
        $suppliers = Supplier::all();
        if ($categories->isEmpty() || $suppliers->isEmpty()) {
            $this->command?->warn('Categories or Suppliers empty - skipping ProductSeeder');
            return;
        }

        $samples = [
            ['name' => 'Laptop', 'price' => 1200.00, 'stock' => 10],
            ['name' => 'T-Shirt', 'price' => 19.99, 'stock' => 100],
            ['name' => 'Basketball', 'price' => 29.50, 'stock' => 40],
        ];

        foreach ($samples as $sample) {
            Product::firstOrCreate(
                ['name' => $sample['name']],
                $sample + [
                    'category_id' => $categories->random()->id,
                    'supplier_id' => $suppliers->random()->id,
                    'image_path' => null,
                ]
            );
        }
    }
}
