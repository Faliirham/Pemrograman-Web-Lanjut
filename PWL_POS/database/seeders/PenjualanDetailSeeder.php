<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenjualanDetailSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['detail_id' => 1, 'penjualan_id' => 1, 'barang_id' => 4, 'jumlah' => 2, 'harga' => 50000],
            ['detail_id' => 2, 'penjualan_id' => 1, 'barang_id' => 5, 'jumlah' => 1, 'harga' => 30000],
            ['detail_id' => 3, 'penjualan_id' => 2, 'barang_id' => 7, 'jumlah' => 3, 'harga' => 75000],
        ];

        DB::table('t_penjualan_detail')->insert($data);
    }
}
