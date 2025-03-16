<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenjualanSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            DB::table('t_penjualan')->insert([
                'penjualan_id' => $i,
                'user_id' => rand(1, 3),
                'pembeli' => 'Pembeli ' . $i,
                'penjualan_kode' => 'PNJ00' . $i,
                'penjualan_tanggal' => now(),
            ]);
        }
    }
}