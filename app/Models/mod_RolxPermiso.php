<?php

namespace App\Models;

use App\Models\mod_Permiso;
use App\Models\mod_Rol;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class mod_RolxPermiso extends Model
{
    use HasFactory, SoftDeletes;

    const DELETED_AT = 'DELETED_AT';
    protected $table = 'ROLXPERMISO';
    protected $fillable = ['ID_ROLES', 'ID_PERMISOS'];
    protected $primaryKey = 'ID';
    protected $dates = ['DELETED_AT'];
    public $timestamps = false;
    public $incrementing = false;

    public function ROLES()
    {
        return $this->belongsTo(mod_Rol::class, 'ID_ROLES', 'ID');
    }

    public function PERMISOS()
    {
        return $this->belongsTo(mod_Permiso::class, 'ID_PERMISOS', 'ID');
    }
}

