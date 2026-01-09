<?php

namespace App\Http\Controllers;

use App\Models\MenuDay;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class PdfController extends Controller
{
    public function userPlatos($date = null)
    {
        $user = Auth::user();
        
        // Si no hay fecha, usamos hoy. Si hay, parseamos la fecha del calendario.
        $fechaReferencia = $date ? Carbon::parse($date) : Carbon::now();
        
        // Calculamos el inicio y fin de la semana para esa fecha específica
        $inicioSemana = $fechaReferencia->copy()->startOfWeek();
        $finSemana = $fechaReferencia->copy()->endOfWeek();

        $consumosSemanales = MenuDay::with(['menu.platos.products'])
            ->where('user_id', $user->id)
            ->whereBetween('day', [$inicioSemana->toDateString(), $finSemana->toDateString()])
            ->orderBy('day', 'asc')
            ->get()
            ->groupBy('day');

        $goals = $user->nutritionalGoal;

        $pdf = Pdf::loadView('pdf.user-platos', [
            'user' => $user,
            'consumosSemanales' => $consumosSemanales,
            'goals' => $goals,
            'rango' => $inicioSemana->format('d/m/Y') . ' al ' . $finSemana->format('d/m/Y')
        ])->setPaper('a4', 'portrait');

        return $pdf->download("Plan_Nutricional_{$inicioSemana->format('d_m_Y')}.pdf");
    }
}