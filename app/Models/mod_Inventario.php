<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class mod_Inventario extends Model
{
    use HasFactory, SoftDeletes;

    const DELETED_AT = 'DELETED_AT';
    protected $table = 'INVENTARIOS';
    protected $fillable = ['NOMBRE', 'ID_FOTOS', 'ID_USUARIOS'];
    protected $dates = ['DELETED_AT'];
    protected $primaryKey = 'ID';
    public $timestamps = false;
    public $incrementing = false;

    public function FOTOS()
    {
        return $this->belongsTo(mod_Foto::class, 'ID_FOTOS');
    }

    public function USUARIOS()
    {
        return $this->belongsTo(mod_Usuario::class, 'ID_USUARIOS');
    }

    

}

