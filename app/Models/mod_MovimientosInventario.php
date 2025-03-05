<?php

namespace App\Models;

use App\Models\mod_Articulo;
use App\Models\mod_Inventario;
use App\Models\mod_Usuario;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class mod_MovimientosInventario extends Model
{
    use HasFactory, SoftDeletes;

    const DELETED_AT = 'DELETED_AT';
    protected $table = 'MOVIMIENTOSINVENTARIOS';
    protected $fillable = ['ID_ARTICULOS', 'ID_INVENTARIOS', 'TIPO', 'CANTIDAD', 'FECHA', 'ID_USUARIOS', 'OBSERVACIONES'];
    protected $dates = ['DELETED_AT'];
    protected $primaryKey = 'ID';
    public $timestamps = false;
    public $incrementing = false;

    
    public function ARTICULOS()
    {
        return $this->belongsTo(mod_Articulo::class, 'ID_ARTICULOS', 'ID');
    }
    
    public function INVENTARIOS()
    {
        return $this->belongsTo(mod_Inventario::class, 'ID_INVENTARIOS', 'ID');
    }
    
    public function USUARIOS()
    {
        return $this->belongsTo(mod_Usuario::class, 'ID_USUARIOS', 'ID');
    }
    
}

