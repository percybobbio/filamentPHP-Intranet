<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    //
    use HasFactory;

    //Esta propiedad indica que todos los campos son asignables en masa, excepto los que se especifiquen en el array. En este caso, no hay campos especificados, por lo que todos los campos de la tabla son asignables en masa. No se recomienda en producción, ya que puede ser un riesgo de seguridad si no se controla adecuadamente.
    protected $guarded = [];
}
