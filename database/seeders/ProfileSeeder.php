<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProfileSeeder extends Seeder
{
    public function run(): void
    {
        $profiles = [
            'admin@example.com' => [
                'headline' => 'Operations Director',
                'bio' => 'Akun admin demo dengan akses penuh untuk mengelola gudang, pelanggan, dan materi ajar.',
                'website' => 'https://example.com/admin-demo',
                'github_handle' => 'admin-demo',
            ],
            'manager@example.com' => [
                'headline' => 'Warehouse Manager',
                'bio' => 'Mengawasi persediaan dan memastikan stok produk selalu akurat.',
                'website' => 'https://example.com/warehouse',
                'github_handle' => 'warehouse-manager',
            ],
            'customer@example.com' => [
                'headline' => 'Loyal Customer',
                'bio' => 'Contoh pelanggan yang masuk untuk melihat penawaran khusus (halaman kosong sementara).',
                'website' => null,
                'github_handle' => 'customer-demo',
            ],
        ];

        foreach ($profiles as $email => $profileData) {
            $user = User::where('email', $email)->first();

            if (! $user) {
                $this->command?->warn("User {$email} belum tersedia untuk dibuatkan profil.");
                continue;
            }

            Profile::updateOrCreate(
                ['user_id' => $user->id],
                $profileData + ['user_id' => $user->id]
            );
        }
    }
}
