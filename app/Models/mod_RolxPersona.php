<?php

namespace App\Models;

use App\Models\mod_Persona;
use App\Models\mod_Rol;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class mod_RolxPersona extends Model
{
    use HasFactory, SoftDeletes;

    const DELETED_AT = 'DELETED_AT';
    protected $table = 'ROLXPERSONA';
    protected $fillable = ['ID_ROLES', 'ID_PERSONAS'];
    protected $primaryKey = 'ID';
    protected $dates = ['DELETED_AT'];
    public $timestamps = false;
    public $incrementing = false;

    public function PERSONAS()
    {
        return $this->belongsTo(mod_Persona::class, 'ID_PERSONAS'); 
    }

    public function ROLES()
    {
        return $this->belongsTo(mod_Rol::class, 'ID_ROLES');
    }
}
