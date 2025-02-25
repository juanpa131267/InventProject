<?php

namespace App\Models;

use App\Models\mod_RolxPersona;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;


class mod_Usuario extends Model implements Authenticatable
{
    use HasFactory, SoftDeletes, AuthenticatableTrait;

    protected $table = 'USUARIOS';  
    protected $fillable = ['ID_PERSONAS', 'LOGIN', 'PASSWORD'];  
    protected $primaryKey = 'ID';
    protected $dates = ['DELETED_AT'];
    public $timestamps = false;
    public $incrementing = false;

    public function PERSONAS()
    {
        return $this->belongsTo(mod_Persona::class, 'ID_PERSONAS');
    }
    
     public function hasRole($role)
     {
         return $this->persona->roles->contains(function($r) use ($role) {
             return $r->rol->descripcion === $role;
         });
     }

// public function tienePermiso($nombrePermiso)
// {
//     $persona = $this->persona;

//     // Verificar si el usuario tiene roles asociados
//     if (!$persona || !$persona->roles) {
//         return false;
//     }

//     // Iterar sobre los roles asociados a la persona
//     foreach ($persona->roles as $rolPersona) {
//         $rol = $rolPersona->rol;

//         // Cargar permisos para el rol si no están cargados
//         if ($rol && !$rol->relationLoaded('permisos')) {
//             $rol->load('permisos');
//         }

//         // Obtener permisos del rol
//         $permisos = $rol->permisos;

//         // Validar que $permisos no esté vacío
//         if (!$permisos || $permisos->isEmpty()) {
//             continue;
//         }

//         // Verificar si el permiso existe en la lista de permisos del rol
//         foreach ($permisos as $permiso) {
//             if ($permiso->nombre === $nombrePermiso) {
//                 return true;
//             }
//         }
//     }
    
//     return false;
// }


    public function rolxpersona()
    {
        return $this->hasOne(mod_RolxPersona::class, 'persona_id', 'persona_id');
    }
        

}

