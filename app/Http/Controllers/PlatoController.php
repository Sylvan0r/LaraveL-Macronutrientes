<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plato;
use Illuminate\Support\Facades\Auth;

class PlatoController extends Controller
{
    public function index()
    {
        $platos = Plato::where('user_id', Auth::id())
            ->orderByDesc('is_favorite') // favoritos primero
            ->orderBy('name')
            ->get();

        return view('platos.mis-platos', compact('platos'));
    }
}
