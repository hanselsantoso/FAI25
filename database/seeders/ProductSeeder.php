<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Product, Category, Supplier, Tag};

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

        $tagCount = Tag::count();

        foreach ($samples as $sample) {
            $product = Product::firstOrCreate(
                ['name' => $sample['name']],
                $sample + [
                    'category_id' => $categories->random()->id,
                    'supplier_id' => $suppliers->random()->id,
                    'image_path' => null,
                ]
            );

            if ($tagCount > 0) {
                $tagIds = Tag::inRandomOrder()
                    ->take(random_int(1, min(3, $tagCount)))
                    ->pluck('id');

                if ($tagIds->isNotEmpty()) {
                    $product->tags()->syncWithoutDetaching($tagIds->all());
                }
            }
        }
    }
}
