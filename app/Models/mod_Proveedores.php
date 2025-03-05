<?php

namespace App\Models;


use App\Models\mod_ArticuloxProveedor;
use App\Models\mod_Articulo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class mod_Proveedores extends Model
{
    use HasFactory, SoftDeletes;

    const DELETED_AT = 'DELETED_AT';
    protected $table = 'PROVEEDORES';
    protected $fillable = ['NOMBRE', 'TELEFONO', 'CORREO', 'DIRECCION'];
    protected $dates = ['DELETED_AT'];
    protected $primaryKey = 'ID';
    public $timestamps = false;
    public $incrementing = false;

    public function ARTICULOXPROVEEDOR()
    {
        return $this->hasMany(mod_ArticuloxProveedor::class, 'ID_PROVEEDORES', 'ID');
    }


}

