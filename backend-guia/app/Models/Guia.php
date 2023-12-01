<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guia extends Model
{
    use HasFactory;
    // name guia table
    protected $table = 'Guia';

    // Specific primary key
    protected $primaryKey = 'guia_id';

    // feels  to guia
    protected $fillable = [
        'guia_id',
        'numero_guia',
        'fecha_envio',
        'pais_origen',
        'nombre_remitente',
        'direccion_remitente',
        'telefono_remitente',
        'email_remitente',
        'pais_destino',
        'nombre_destinatario',
        'direccion_destinatario',
        'telefono_destinatario',
        'email_destinatario',
        'total',
    ];

    // one to many relation with facturas
    public function facturas()
    {
        return $this->belongsToMany(Factura::class, 'guia_factura', 'fk_guia_id', 'fk_factura_id');
    }


}
