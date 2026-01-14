<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'status' => true,
            'message' => 'Lista de productos en BD',
            'data' => product::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'calories' => 'nullable|numeric',
            'total_fat' => 'nullable|numeric',
            'saturated_fat' => 'nullable|numeric',
            'trans_fat' => 'nullable|numeric',
            'colesterol' => 'nullable|numeric',
            'polyunsaturated_fat' => 'nullable|numeric',
            'monounsaturated_fat' => 'nullable|numeric',
            'carbohydrates' => 'nullable|numeric',
            'fiber' => 'nullable|numeric',
            'proteins' => 'nullable|numeric',
            'category_id' => 'required|exists:categories,id'
        ]);

        // 2. Verificar autenticación (opcional si usas el middleware)
        if (Auth::check()) {
            // 3. Crear el producto
            $product = Product::create($validatedData);

            return response()->json([
                'status' => true,
                'message' => 'Producto creado con éxito',
                'data' => $product
            ], 201); // 201 significa "Created"
        }

        return response()->json([
            'status' => false,
            'message' => 'No autorizado',
        ], 401);
    }

    /**
     * Display the specified resource.
     */
    public function show(product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) // Recibes el ID directamente
    {
        // 1. Validar los datos de entrada
        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'calories' => 'nullable|numeric',
            'total_fat' => 'nullable|numeric',
            'saturated_fat' => 'nullable|numeric',
            'trans_fat' => 'nullable|numeric',
            'colesterol' => 'nullable|numeric',
            'polyunsaturated_fat' => 'nullable|numeric',
            'monounsaturated_fat' => 'nullable|numeric',
            'carbohydrates' => 'nullable|numeric',
            'fiber' => 'nullable|numeric',
            'proteins' => 'nullable|numeric',
            'category_id' => 'sometimes|required|exists:categories,id'
        ]);

        // 2. Verificar autenticación
        if (Auth::check()) {
            
            // 3. Buscar el producto por el ID que viene en la URL
            $product = Product::find($id);

            // 4. Verificar si el producto existe
            if (!$product) {
                return response()->json([
                    'status' => false,
                    'message' => 'Producto no encontrado',
                ], 404);
            }

            // 5. Actualizar el producto encontrado
            $product->update($validatedData);

            return response()->json([
                'status' => true,
                'message' => 'Producto actualizado con éxito',
                'data' => $product
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => 'No autorizado',
        ], 401);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(product $product)
    {
        //
    }
}
