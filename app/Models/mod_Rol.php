<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class mod_Rol extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ROLES';  
    protected $fillable = ['DESCRIPCION'];
    protected $dates = ['DELETED_AT'];
    public $timestamps = false;
    public $incrementing = false;

    public function PERSONAS()
    {
        return $this->hasMany(mod_RolxPersona::class, 'ROLES_ID');
    }

    public function PERMISOS()
    {
        return $this->belongsToMany(mod_Permiso::class, 'ROLXPERMISO', 'ROLES_ID', 'PERMISOS_ID');
    }

    
}

// los roles serán:
// 1. Administrador
// 2. Usuario
