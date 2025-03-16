<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('m_barang')->insert([
            // Barang dari Supplier A
            ['barang_id' => 1, 'kategori_id' => 1, 'barang_kode' => 'BRG001', 'barang_nama' => 'Laptop Asus', 'harga_beli' => 7000000, 'harga_jual' => 7500000],
            ['barang_id' => 2, 'kategori_id' => 2, 'barang_kode' => 'BRG002', 'barang_nama' => 'Baju Kemeja', 'harga_beli' => 120000, 'harga_jual' => 150000],
            ['barang_id' => 3, 'kategori_id' => 3, 'barang_kode' => 'BRG003', 'barang_nama' => 'Roti Gandum', 'harga_beli' => 20000, 'harga_jual' => 25000],
            ['barang_id' => 4, 'kategori_id' => 4, 'barang_kode' => 'BRG004', 'barang_nama' => 'Susu UHT', 'harga_beli' => 10000, 'harga_jual' => 15000],
            ['barang_id' => 5, 'kategori_id' => 5, 'barang_kode' => 'BRG005', 'barang_nama' => 'Jam Tangan', 'harga_beli' => 250000, 'harga_jual' => 300000],

            // Barang dari Supplier B
            ['barang_id' => 6, 'kategori_id' => 1, 'barang_kode' => 'BRG006', 'barang_nama' => 'Monitor LG', 'harga_beli' => 1500000, 'harga_jual' => 1800000],
            ['barang_id' => 7, 'kategori_id' => 2, 'barang_kode' => 'BRG007', 'barang_nama' => 'Celana Jeans', 'harga_beli' => 200000, 'harga_jual' => 250000],
            ['barang_id' => 8, 'kategori_id' => 3, 'barang_kode' => 'BRG008', 'barang_nama' => 'Mie Instan', 'harga_beli' => 3000, 'harga_jual' => 5000],
            ['barang_id' => 9, 'kategori_id' => 4, 'barang_kode' => 'BRG009', 'barang_nama' => 'Teh Botol', 'harga_beli' => 4000, 'harga_jual' => 6000],
            ['barang_id' => 10, 'kategori_id' => 5, 'barang_kode' => 'BRG010', 'barang_nama' => 'Kacamata Hitam', 'harga_beli' => 100000, 'harga_jual' => 130000],

            // Barang dari Supplier C
            ['barang_id' => 11, 'kategori_id' => 1, 'barang_kode' => 'BRG011', 'barang_nama' => 'Smartphone Xiaomi', 'harga_beli' => 3000000, 'harga_jual' => 3500000],
            ['barang_id' => 12, 'kategori_id' => 2, 'barang_kode' => 'BRG012', 'barang_nama' => 'Kaos Polos', 'harga_beli' => 50000, 'harga_jual' => 70000],
            ['barang_id' => 13, 'kategori_id' => 3, 'barang_kode' => 'BRG013', 'barang_nama' => 'Keripik Singkong', 'harga_beli' => 15000, 'harga_jual' => 20000],
            ['barang_id' => 14, 'kategori_id' => 4, 'barang_kode' => 'BRG014', 'barang_nama' => 'Jus Buah', 'harga_beli' => 12000, 'harga_jual' => 15000],
            ['barang_id' => 15, 'kategori_id' => 5, 'barang_kode' => 'BRG015', 'barang_nama' => 'Gelang Karet', 'harga_beli' => 5000, 'harga_jual' => 8000],
        ]);
    }
}