<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StokSeeder extends Seeder
{
    public function run(): void
    {   
        for ($i = 1; $i <= 15; $i++) {
            DB::table('t_stok')->insert([
                'stok_id' => $i,
                'barang_id' => $i,
                'supplier_id' => ($i % 3) + 1, 
                'user_id' => 1, 
                'stok_tanggal' => now(),
                'stok_jumlah' => rand(5, 20),
            ]);
        }
    }
}