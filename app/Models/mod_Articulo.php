<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class mod_Articulo extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ARTICULOS';
    protected $fillable = ['ID_INVENTARIOS', 'NOMBRE', 'MARCA', 'DESCRIPCION', 'FECHACADUCIDAD', 'UNIDAD', 'CANTIDAD'];
    protected $dates = ['DELETED_AT'];
    protected $primaryKey = 'ID';
    public $timestamps = false;
    public $incrementing = false;

    public function INVENTARIO()
    {
        return $this->belongsTo(mod_Inventario::class, 'ID_INVENTARIO');
    }

}

