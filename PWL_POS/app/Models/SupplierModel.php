<?php
 
 namespace App\Models;
 
 use Illuminate\Database\Eloquent\Factories\HasFactory;
 use Illuminate\Database\Eloquent\Model;
 
 class SupplierModel extends Model
 {
     use HasFactory;
     protected $table = 'm_supplier'; // Nama tabel
     protected $primaryKey = 'supplier_id'; // Primary key
     public $timestamps = true; // Menggunakan timestamps
 
     protected $fillable = ['supplier_kode', 'supplier_nama', 'supplier_alamat'];
 }