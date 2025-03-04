<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class mod_Categoria extends Model
{
    use HasFactory, SoftDeletes;

    const DELETED_AT = 'DELETED_AT';
    protected $table = 'CATEGORIAS';
    protected $fillable = ['NOMBRE'];
    protected $dates = ['DELETED_AT'];
    protected $primaryKey = 'ID';
    public $timestamps = false;
    public $incrementing = false;

    public function ARTICULOXCATEGORIA()
    {
        return $this->hasMany(mod_ArticuloxCategoria::class, 'ID_CATEGORIAS', 'ID');
    }

}

