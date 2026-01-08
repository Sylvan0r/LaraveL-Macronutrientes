<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {   
        // Cambiado 'user_id' por 'id_user' para que coincida con tu base de datos
        $userProducts = Product::where('id_user', Auth::id())
            ->orderByDesc('is_favorite')
            ->orderBy('name')
            ->get();

        // Usamos compact con el nombre correcto de la variable
        return view('platos.mis-productos', compact('userProducts'));
    }
}