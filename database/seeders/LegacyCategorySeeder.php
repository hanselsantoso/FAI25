<?php

namespace Database\Seeders;

use App\Models\LegacyCategory;
use Illuminate\Database\Seeder;

class LegacyCategorySeeder extends Seeder
{
    public function run(): void
    {
        $records = [
            ['code' => 'BTL', 'title' => 'Bantal Warisan', 'is_active' => true],
            ['code' => 'KST', 'title' => 'Kasut Antik', 'is_active' => true],
            ['code' => 'GNP', 'title' => 'Gantungan Pusaka', 'is_active' => false],
        ];

        foreach ($records as $record) {
            LegacyCategory::updateOrCreate(
                ['code' => $record['code']],
                $record
            );
        }
    }
}
