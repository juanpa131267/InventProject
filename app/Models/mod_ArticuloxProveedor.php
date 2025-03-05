<?php

namespace App\Models;

use App\Models\mod_ArticuloxProveedor;
use App\Models\mod_Articulo;
use App\Models\mod_Proveedores;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class mod_ArticuloxProveedor extends Model
{
    use HasFactory, SoftDeletes;

    const DELETED_AT = 'DELETED_AT';
    protected $table = 'ARTICULOXPROVEEDOR';
    protected $fillable = ['ID_ARTICULOS', 'ID_PROVEEDORES'];
    protected $dates = ['DELETED_AT'];
    protected $primaryKey = 'ID';
    public $timestamps = false;
    public $incrementing = false;

    
    public function ARTICULOS()
    {
        return $this->belongsTo(mod_Articulo::class, 'ID_ARTICULOS', 'ID');
    }
    
    public function PROVEEDORES()
    {
        return $this->belongsTo(mod_Proveedores::class, 'ID_PROVEEDORES', 'ID');
    }
    
}
