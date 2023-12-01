<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

//Models
use App\Models\Guia;

class GuiaSearchController extends Controller
{
    public function search($texto)
    {
        $query = Guia::query();

        // Buscar por texto en varios campos
        if ($search = $texto) {
            $query->where(function($q) use ($search) {
                $q->where('guia_id', 'LIKE', "%{$search}%")
                  ->orWhere('numero_guia', 'LIKE', "%{$search}%")
                  ->orWhere('fecha_envio', 'LIKE', "%{$search}%")
                  ->orWhere('pais_origen', 'LIKE', "%{$search}%")
                  ->orWhere('nombre_remitente', 'LIKE', "%{$search}%")
                  ->orWhere('direccion_remitente', 'LIKE', "%{$search}%")
                  ->orWhere('telefono_remitente', 'LIKE', "%{$search}%")
                  ->orWhere('email_remitente', 'LIKE', "%{$search}%")
                  ->orWhere('pais_destino', 'LIKE', "%{$search}%")
                  ->orWhere('nombre_destinatario', 'LIKE', "%{$search}%")
                  ->orWhere('direccion_destinatario', 'LIKE', "%{$search}%")
                  ->orWhere('telefono_destinatario', 'LIKE', "%{$search}%")
                  ->orWhere('email_destinatario', 'LIKE', "%{$search}%")
                  ->orWhere('total', 'LIKE', "%{$search}%");
            });
        }

        // Incluir relaciones si es necesario
        $query->with(['facturas' => function ($q) {
            $q->select('factura_id',
                        'establecimiento',
                        'punto_emision',
                        'secuencial',
                        'fecha_emision',
                        'sub_total',
                        'impuesto',
                        'total'); 
        }]);

        $guias = $query->orderBy('fecha_envio', 'DESC')->get();

        return response()->json($guias);
    }
}
