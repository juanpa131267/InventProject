<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class mod_Proveedores extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'PROVEEDORES';
    protected $fillable = ['NOMBRE', 'TELEFONO', 'CORREO', 'DIRECCION'];
    protected $dates = ['DELETED_AT'];
    protected $primaryKey = 'ID';
    public $timestamps = false;
    public $incrementing = false;


}

