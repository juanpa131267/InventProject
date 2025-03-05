<?php

namespace App\Models;

use App\Models\mod_Rol;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class mod_Permiso extends Model
{
    use HasFactory, SoftDeletes;

    const DELETED_AT = 'DELETED_AT';
    protected $table = 'PERMISOS';
    protected $fillable = ['NOMBRE', 'DESCRIPCION'];
    protected $primaryKey = 'ID';
    public $timestamps = false;
    public $incrementing = false;

    public function ROLES()
    {
        return $this->belongsToMany(mod_Rol::class, 'ROLXPERMISO', 'ID_PERMISOS', 'ID_ROLES');
    }
    
    public function ROLXPERMISO()
    {
        return $this->hasMany(mod_RolxPermiso::class, 'ID_PERMISO', 'ID');
    }

}


// los permisos serán:
// 1. crear_Inventario
// 2. editar_Inventario
// 3. eliminar_Inventario
// 4. ver_Inventario
