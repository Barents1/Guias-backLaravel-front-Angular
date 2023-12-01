<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Symfony\Component\Console\Output\ConsoleOutput;

// Models
use App\Models\Factura;
use App\Models\Guia;

class FacturaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // validation 
        try {
            // select all facturas
            $facturas = Factura::all();
            return response()->json(['data' => $facturas], 200);
        } catch (Exception $e ) {
            // return message error
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        try {

            // validate if exist guias add
            $this->validarGuias($request->guias_ids);

            DB::beginTransaction();

            // created factura
            // Asumiendo que 'total' es un campo calculado, aquí deberías calcularlo
            // basándote en las guías seleccionadas
            // $result = $this->calcularTotal($request->guias_ids); 
            // $data = $result->getData();
            $facturaData = $request->all();
            // // add to facturaData the cost total according to the quantity
            // $facturaData['sub_total'] = $data->data->subtotal;
            // $facturaData['impuesto'] = $data->data->impuesto;
            // $facturaData['total'] = $data->data->total;
            $factura = Factura::create($facturaData);

            $factura->guias()->syncWithoutDetaching($request->guias_ids);

            DB::commit();
            return response()->json(['message' => 'Factura created successfully', 'factura' => $factura], 201);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function countFacturas(Request $request)
    {
        try {
            // select all facturas
            $num_registros = Factura::count();
            return response()->json(['data' => $num_registros], 200);
        } catch (Exception $e ) {
            // return message error
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            // shearch fatura with specific id
            $result = Factura::find($id);
            return $result;
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
        
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
    public function update(Request $request, string $facturaId)
    {
        try {
            DB::beginTransaction();

            $factura = Factura::findOrFail($facturaId);

            // update details the factura
            $factura->update($request->except('guias_ids'));

            // Si se proporcionan guías, actualizar las asociaciones
            if ($request->has('guias_ids')) {
                // Validar las guías antes de proceder
                // $this->validarGuias($request->guias_ids, $facturaId);

                // Actualizar las asociaciones de guías
                $factura->guias()->sync($request->guias_ids);
            }

            DB::commit();
            return response()->json(['message' => 'Factura updated successfully', 'factura' => $factura], 200);
        } catch (Exception $e) {
            DB::rollBack();
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
            $result = Factura::find($id);
            // validate if exist factura with specific id
            if (!$result) {
                return response()->json(['message' => 'Guia not found'], 404);
            }
            //delete data
            $result->delete();
            // return delete successfully
        return response()->json(['Data delete successfull' => 200]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

     /**
     * Method to total calculator.
     */
    public function calcularTotal(Request $request)
    {
        $guiasIds = $request->input('guias_ids');
        $sub_total = 0;
        $impuesto=0;
        foreach ($guiasIds as $guiaId) {
            $guia = Guia::findOrFail($guiaId);
            // get the total the value of the guia
            $sub_total += $guia->total;
        }
        // print($total);
        // add the 12% to subtotal value
        $impuesto = $sub_total * 0.12;
        $total = $sub_total + $impuesto;
        $data = [
            'subtotal' => $sub_total,
            'impuesto' => $impuesto,
            'total' => $total
        ];
        // $total = $total+$sub_total;

        return response()->json(['data' => $data]);
    }

    /**
     * Method to validate guias.
     */
    private function validarGuias($guiasIds)
    {
        foreach ($guiasIds as $guiaId) {
            // Check if the guide is already exist
            $count = DB::table('guia_factura')
                        ->where('fk_guia_id', $guiaId)
                        ->count();

            if ($count > 0) {
                throw new Exception("La guía $guiaId ya está facturada.");
                
            }
        }
    }
}
