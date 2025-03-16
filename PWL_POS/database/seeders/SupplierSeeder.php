<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('m_supplier')->insert([
            ['supplier_id' => 1, 'supplier_kode' => 'SUP001', 'supplier_nama' => 'Supplier A', 'supplier_alamat' => 'Jl. Raya No. 1'],
            ['supplier_id' => 2, 'supplier_kode' => 'SUP002', 'supplier_nama' => 'Supplier B', 'supplier_alamat' => 'Jl. Kebon No. 5'],
            ['supplier_id' => 3, 'supplier_kode' => 'SUP003', 'supplier_nama' => 'Supplier C', 'supplier_alamat' => 'Jl. Merdeka No. 10'],
        ]);
    }
}