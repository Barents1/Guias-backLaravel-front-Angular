<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Factura extends Model
{
    use HasFactory;

     // name factura table
     protected $table = 'facturas';

     // Specific primary key
     protected $primaryKey = 'factura_id';
 
     // feels  to factura
     protected $fillable = [
        'factura_id',
        'establecimiento',
        'punto_emision',
        'secuencial',
        'fecha_emision',
        'sub_total',
        'impuesto',
        'total',
     ];
 
     // many to many relation with facturas
     public function guias(){
         return $this->belongsToMany(Guia::class, 'guia_factura', 'fk_factura_id', 'fk_guia_id');
     }


     // oneto many relation with pagos
     public function pagos()
     {
         return $this->hasMany(Pago::class, 'factura_id');
     }
}
