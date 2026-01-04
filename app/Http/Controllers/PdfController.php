<?php

namespace App\Http\Controllers;

use App\Models\Plato;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfController extends Controller
{
    public function userPlatos()
    {
        $user = Auth::user();

        $platos = Plato::with('products')
            ->where('user_id', $user->id)
            ->get();

        $pdf = Pdf::loadView('pdf.user-platos', compact('platos', 'user'))
                  ->setPaper('a4', 'portrait');

        return $pdf->download('mis_platos.pdf');
    }
}
