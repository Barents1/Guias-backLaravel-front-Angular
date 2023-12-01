<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pagos extends Model
{
    use HasFactory;

    // name pagos table
    protected $table = 'pagos';

    // Specific primary key
    protected $primaryKey = 'pago_id';

    // feels  to guia
    protected $fillable = [
        'tipo_pago',
        'valor',
        'fk_factura_id',
    ];

    // many to one relation with facturas
    public function factura()
    {
        return $this->belongsTo(Factura::class, 'fk_factura_id');
    }
}
