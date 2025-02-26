<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class mod_Foto extends Model
{
    use HasFactory, SoftDeletes;

    const DELETED_AT = 'DELETED_AT';
    protected $table = 'FOTOS';
    protected $fillable = ['URL', 'DESCRIPCION'];
    protected $dates = ['DELETED_AT'];
    protected $primaryKey = 'ID';
    public $timestamps = false;
    public $incrementing = false;

    
    public function INVENTARIO()
    {
        return $this->belongsTo(mod_Inventario::class, 'ID_FOTOS');
    }

    public function ARTICULOXFOTO()
    {
        return $this->hasMany(mod_ArticuloxFoto::class, 'ID_FOTOS');
    }

}

