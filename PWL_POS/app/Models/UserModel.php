<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;


class UserModel extends Authenticatable 
{
    use HasFactory;

    protected $table = 'm_user';
    protected $primaryKey = 'user_id';
    protected $fillable = ['level_id', 'username', 'nama','password','foto'];

    protected $hidden = ['password']; // jangan ditampilkan saat select

    protected $casts =['password' => 'hashed']; // casting password di hash
    public function level():BelongsTo
    { 
        return $this->belongsTo(LevelModel::class, 'level_id', 'level_id');
    }

    public function getRoleName (){
        return $this->level->level_nama;
    }

    public function hasRole ($role) : bool{
        return $this->level->level_kode == $role;
    }

    public function getRole(){
        return $this->level->level_kode;
    }
}