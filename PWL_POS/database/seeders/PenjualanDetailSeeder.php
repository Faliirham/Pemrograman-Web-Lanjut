<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenjualanDetailSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            // Penjualan 1
            ['detail_id' => 1, 'penjualan_id' => 1, 'barang_id' => 1, 'jumlah' => 2, 'harga' => 50000],
            ['detail_id' => 2, 'penjualan_id' => 1, 'barang_id' => 2, 'jumlah' => 1, 'harga' => 75000],
            ['detail_id' => 3, 'penjualan_id' => 1, 'barang_id' => 3, 'jumlah' => 3, 'harga' => 60000],

            // Penjualan 2
            ['detail_id' => 4, 'penjualan_id' => 2, 'barang_id' => 4, 'jumlah' => 1, 'harga' => 30000],
            ['detail_id' => 5, 'penjualan_id' => 2, 'barang_id' => 5, 'jumlah' => 2, 'harga' => 40000],
            ['detail_id' => 6, 'penjualan_id' => 2, 'barang_id' => 6, 'jumlah' => 4, 'harga' => 55000],

            // Penjualan 3
            ['detail_id' => 7, 'penjualan_id' => 3, 'barang_id' => 7, 'jumlah' => 2, 'harga' => 80000],
            ['detail_id' => 8, 'penjualan_id' => 3, 'barang_id' => 8, 'jumlah' => 3, 'harga' => 65000],
            ['detail_id' => 9, 'penjualan_id' => 3, 'barang_id' => 9, 'jumlah' => 1, 'harga' => 70000],

            // Penjualan 4
            ['detail_id' => 10, 'penjualan_id' => 4, 'barang_id' => 10, 'jumlah' => 3, 'harga' => 50000],
            ['detail_id' => 11, 'penjualan_id' => 4, 'barang_id' => 11, 'jumlah' => 2, 'harga' => 60000],
            ['detail_id' => 12, 'penjualan_id' => 4, 'barang_id' => 12, 'jumlah' => 1, 'harga' => 55000],

            // Penjualan 5
            ['detail_id' => 13, 'penjualan_id' => 5, 'barang_id' => 13, 'jumlah' => 4, 'harga' => 75000],
            ['detail_id' => 14, 'penjualan_id' => 5, 'barang_id' => 14, 'jumlah' => 1, 'harga' => 50000],
            ['detail_id' => 15, 'penjualan_id' => 5, 'barang_id' => 15, 'jumlah' => 2, 'harga' => 70000],

            // Penjualan 6
            ['detail_id' => 16, 'penjualan_id' => 6, 'barang_id' => 1, 'jumlah' => 3, 'harga' => 65000],
            ['detail_id' => 17, 'penjualan_id' => 6, 'barang_id' => 2, 'jumlah' => 2, 'harga' => 55000],
            ['detail_id' => 18, 'penjualan_id' => 6, 'barang_id' => 3, 'jumlah' => 1, 'harga' => 60000],

            // Penjualan 7
            ['detail_id' => 19, 'penjualan_id' => 7, 'barang_id' => 4, 'jumlah' => 2, 'harga' => 40000],
            ['detail_id' => 20, 'penjualan_id' => 7, 'barang_id' => 5, 'jumlah' => 3, 'harga' => 70000],
            ['detail_id' => 21, 'penjualan_id' => 7, 'barang_id' => 6, 'jumlah' => 1, 'harga' => 80000],

            // Penjualan 8
            ['detail_id' => 22, 'penjualan_id' => 8, 'barang_id' => 7, 'jumlah' => 3, 'harga' => 50000],
            ['detail_id' => 23, 'penjualan_id' => 8, 'barang_id' => 8, 'jumlah' => 2, 'harga' => 45000],
            ['detail_id' => 24, 'penjualan_id' => 8, 'barang_id' => 9, 'jumlah' => 4, 'harga' => 60000],

            // Penjualan 9
            ['detail_id' => 25, 'penjualan_id' => 9, 'barang_id' => 10, 'jumlah' => 1, 'harga' => 75000],
            ['detail_id' => 26, 'penjualan_id' => 9, 'barang_id' => 11, 'jumlah' => 3, 'harga' => 50000],
            ['detail_id' => 27, 'penjualan_id' => 9, 'barang_id' => 12, 'jumlah' => 2, 'harga' => 55000],

            // Penjualan 10
            ['detail_id' => 28, 'penjualan_id' => 10, 'barang_id' => 13, 'jumlah' => 4, 'harga' => 60000],
            ['detail_id' => 29, 'penjualan_id' => 10, 'barang_id' => 14, 'jumlah' => 1, 'harga' => 70000],
            ['detail_id' => 30, 'penjualan_id' => 10, 'barang_id' => 15, 'jumlah' => 2, 'harga' => 50000],
        ];

        DB::table('t_penjualan_detail')->insert($data);
    }
}