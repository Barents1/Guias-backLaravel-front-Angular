<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

//Models
use App\Models\Pagos;
use App\Models\Factura;


class PagosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // to validation 
        try {
            // select all pagos
            $pagos = Pagos::all();
            return $pagos;
        } catch (Exception $e ) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        //
        try {
            // Verificar si la factura existe
            $factura_id = $request->input('fk_factura_id');
            $nuevoValorPago = $request->input('total');

            // Validar el total de pagos
            $this->validarTotalPagos($factura_id, $nuevoValorPago);

            $factura = Factura::find($factura_id);
            if (!$factura) {
                return response()->json(['message' => "La factura con el ID $factura_id no existe."], 404);
            }

            $result = Pagos::create($request->all());
            return response()->json(['message' => 'Pago creado exitosamente', 'data' => $result], 201);

        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $result = Pagos::find($id);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
        return  $result;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $pagoId)
    {
        try {
            // Buscar el pago a actualizar
            $pago = Pagos::findOrFail($pagoId);
    
            // Opcional: Verificar si la factura existe (si se actualiza la factura asociada)
            if ($request->has('fk_factura_id')) {

                $factura_id = $request->input('fk_factura_id') ?? $pago->fk_factura_id;
                $nuevoValorPago = $request->input('valor');

                // Validar el total de pagos considerando el nuevo valor y restando el valor antiguo del pago
                $this->validarTotalPagos($factura_id, $nuevoValorPago);
                $factura = Factura::find($factura_id);
                if (!$factura) {
                    return response()->json(['message' => "La factura con el ID $factura_id no existe."], 404);
                }
            }
    
            // Actualizar el pago con los nuevos datos
            // $pago->update($request->all());
            // $this->create();
            return $this->crearNuevoPago($request, $factura->id);
            return response()->json(['message' => 'Pago actualizado exitosamente', 'data' => $pago], 200);
    
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        try {
            $result = Pagos::find($id);
            if (!$result) {
                return response()->json(['message' => 'Pagos not found'], 404);
            }
            $result->delete();
            return response()->json(['Data delete successfull' => 200]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
        
    }

    /**
     * Method to validate pagos.
     */
    private function validarTotalPagos($facturaId, $nuevoValorPago)
    {
        $factura = Factura::find($facturaId);
        // validate if not exist factura with factura_id
        if (!$factura) {
            return response()->json(['message' => "La factura con el ID $facturaId no existe."], 404);
        }
        // query the pagos table using the invoice foreign key
        $totalPagos = Pagos::where('fk_factura_id', $facturaId)->sum('valor');
        // $totalPagos = $totalPagos + $nuevoValorPago;
        $total_factura = $factura->total;
        $saldo_pendiente = $total_factura - $totalPagos;
        // Log::info($totalPagos);
        
        // validate if exceed the payment
        if ($totalPagos + $nuevoValorPago > $total_factura ) {
            // (44.90 + 50.4) > 50.4
            // 50.4 - 95.3 = -44.9
            return throw new Exception("La suma de los pagos excede el total de la factura. total factura = $total_factura: saldo pendiente: $saldo_pendiente");
        // validate if exist pending payments 
        }else if ($totalPagos != $total_factura) {
            return $saldo_pendiente;
        // validate if not exist pending payments
        }else{
            throw new Exception("No tienes saldo pendientes.");
        }
    }

    /**
     * Method to created new pago.
     */
    private function crearNuevoPago(Request $request, $facturaId = null)
    {
        try {
            // Si facturaId no es proporcionada, tomarla del request
            $facturaId = $facturaId ?? $request->input('fk_factura_id');

            // Validar y crear el pago
            $this->validarTotalPagos($facturaId, $request->input('valor'));
            $result = Pagos::create($request->all());

            return response()->json(['message' => 'Pago creado exitosamente', 'data' => $result], 201);

        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
