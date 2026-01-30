<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use Illuminate\Support\Facades\Auth;

class MenuController extends Controller
{
    public function index()
    {
        // Obtenemos los menús del usuario
        $userMenus = Menu::where('user_id', Auth::id())
            ->orderBy('name')
            ->get();

        // IMPORTANTE: Retornamos la vista de menús, no la de platos.
        // Usamos el nombre de variable que espera tu vista o el componente.
        return view('platos.mis-menus', compact('userMenus'));
    }

    public function destroy(Menu $menu)
    {
        abort_unless(auth()->user()->can('eliminar menus'), 403);

        $menu->delete();

        return redirect()->route('menus.index')
            ->with('success', 'Menú eliminado');
    }
}