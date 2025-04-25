<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class LevelModel extends Model
{
    use HasFactory;
    protected $table = 'm_level'; // Nama tabel di database
    protected $primaryKey = 'level_id'; // Primary key
    public $timestamps = false; // Jika tidak ada created_at & updated_at

    protected $fillable = ['level_kode','level_nama']; 

    // Relasi One to One ke UserModel
    public function user():HasOne
    {
        return $this->hasOne(UserModel::class, 'level_id', 'level_id');
    }
}
