<?php

namespace App\Models;

use App\Models\mod_MovimientosInventario;
use App\Models\mod_Inventario;
use App\Models\mod_Usuario;
use App\Models\mod_ArticuloxProveedor;
use App\Models\mod_Proveedores;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class mod_Articulo extends Model
{
    use HasFactory, SoftDeletes;

    const DELETED_AT = 'DELETED_AT';
    protected $table = 'ARTICULOS';
    protected $fillable = ['ID_INVENTARIOS', 'NOMBRE', 'MARCA', 'DESCRIPCION', 'FECHACADUCIDAD', 'UNIDAD', 'CANTIDAD'];
    protected $dates = ['DELETED_AT'];
    protected $primaryKey = 'ID';
    public $timestamps = false;
    public $incrementing = false;

    public function INVENTARIOS()
    {
        return $this->belongsTo(mod_Inventario::class, 'ID_INVENTARIOS', 'ID');
    }

    public function ARTICULOXFOTO()
    {
        return $this->hasMany(mod_ArticuloxFoto::class, 'ID_ARTICULOXFOTO', 'ID');
    }

    public function ARTICULOXCATEGORIA()
    {
        return $this->hasMany(mod_ArticuloxCategoria::class, 'ID_ARTICULOS', 'ID');
    }

    public function ARTICULOXPROVEEDOR()
    {
        return $this->hasMany(mod_ArticuloxProveedor::class, 'ID_ARTICULOS', 'ID');
    }



}

