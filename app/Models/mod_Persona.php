<?php

namespace App\Models;

use App\Models\mod_Usuario;
use App\Models\mod_RolxPersona; 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OpenApi\Annotations as OA;

class mod_Persona extends Model
{
    use HasFactory, SoftDeletes;

    const DELETED_AT = 'DELETED_AT';
    protected $table = 'PERSONAS';
    protected $fillable = ['CEDULA', 'NOMBRES', 'APELLIDO', 'TELEFONO', 'CORREO'];
    protected $dates = ['DELETED_AT'];
    protected $primaryKey = 'ID';
    public $timestamps = false;
    public $incrementing = false;

    
    public function USUARIOS()
    {
        return $this->hasOne(mod_Usuario::class, 'ID_PERSONAS'); 
    }

    public function ROLES()
    {
        return $this->hasMany(mod_RolxPersona::class, 'ID_PERSONAS');
    }

    public function ROLXPERSONA()
    {
        return $this->hasMany(mod_RolxPersona::class, 'ID_PERSONAS');
    }

}

