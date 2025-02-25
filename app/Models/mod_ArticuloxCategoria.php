<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class mod_ArticuloxCategoria extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ARTICULOXCATEGORIA';
    protected $fillable = ['ID_ARTICULOS', 'ID_CATEGORIAS'];
    protected $dates = ['DELETED_AT'];
    protected $primaryKey = 'ID';
    public $timestamps = false;
    public $incrementing = false;

    
    public function ARTICULOS()
    {
        return $this->belongsTo(mod_Articulo::class, 'ID_ARTICULOS');
    }

    public function CATEGORIAS()
    {
        return $this->belongsTo(mod_Categoria::class, 'ID_CATEGORIAS');
    }
}
