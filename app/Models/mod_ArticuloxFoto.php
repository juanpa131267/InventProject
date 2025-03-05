<?php

namespace App\Models;

use App\Models\mod_Articulo;
use App\Models\mod_Foto;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class mod_ArticuloxFoto extends Model
{
    use HasFactory, SoftDeletes;

    const DELETED_AT = 'DELETED_AT';
    protected $table = 'ARTICULOXFOTO';
    protected $fillable = ['ID_ARTICULOS', 'ID_FOTOS'];
    protected $dates = ['DELETED_AT'];
    protected $primaryKey = 'ID';
    public $timestamps = false;
    public $incrementing = false;

    
    public function ARTICULOS()
    {
        return $this->belongsTo(mod_Articulo::class, 'ID_ARTICULOS', 'ID');
    }

    public function FOTOS()
    {
        return $this->belongsTo(mod_Foto::class, 'ID_FOTOS', 'ID');
    }
}

