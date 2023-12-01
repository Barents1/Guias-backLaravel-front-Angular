<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;

// Models 
use App\Models\Guia;
use App\Models\Factura;


class GuiaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // validation 
        try {
            // select all guias ordering by fecha envio
            
            $guias = Guia::orderBy('updated_at', 'DESC')->get();
            return $guias;
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
            $result = Guia::create($request->all());
            return response()->json(['message' => 'Guia created successfully', 'data' => $result], 201);
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
            $result = Guia::find($id);
            return response()->json(['data' => $result], 200);
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
    public function update(Request $request, string $id)
    {
        //
        try {
            $result = Guia::find($id);
            if (!$result) {
                return response()->json(['message' => 'Guia not found'], 404);
            }

            $result->update($request->all());
            return response()->json($result, 200);
    
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        try {
            $result = Guia::find($id);
            if (!$result) {
                return response()->json(['message' => 'Guia not found'], 404);
            }
            $result->delete();
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
        return response()->json(['successfull' => 200]);
    }
}
