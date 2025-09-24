<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $names = ['Electronics', 'Books', 'Clothing', 'Home', 'Sports'];
        foreach ($names as $name) {
            Category::firstOrCreate(['name' => $name]);
        }
    }
}
