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

    public function destroy(Plato $plato)
    {
        abort_unless(auth()->user()->can('eliminar platos'), 403);

        $plato->delete();

        return redirect()->route('platos.index')
            ->with('success', 'Plato eliminado');
    }
}