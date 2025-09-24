<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['name' => 'Acme Corp', 'contact' => 'acme@example.com'],
            ['name' => 'Global Supplies', 'contact' => 'global@example.com'],
            ['name' => 'TechSource', 'contact' => 'tech@example.com'],
        ];
        foreach ($data as $row) {
            Supplier::firstOrCreate(['name' => $row['name']], $row);
        }
    }
}
