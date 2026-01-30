<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Plato;
use App\Models\Menu;

class StatsController extends Controller
{
    public function index()
    {
        $data = [
            'productos' => Product::count(),
            'platos' => Plato::count(),
            'menus' => Menu::count(),
        ];

        return view('admin.stats', $data);
    }
}
