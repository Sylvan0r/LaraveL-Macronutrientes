<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 0.8cm; }
        body { font-family: 'Helvetica', sans-serif; font-size: 10px; color: #333; line-height: 1.4; }
        
        /* Estilo inspirado en tu Dashboard */
        .header { border-bottom: 4px solid #fbbf24; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 18px; color: #111827; text-transform: uppercase; }
        
        .dia-seccion { margin-bottom: 30px; page-break-inside: avoid; }
        .dia-titulo { 
            background-color: #1f2937; color: #fbbf24; 
            padding: 6px 15px; font-weight: bold; border-radius: 5px;
            text-transform: uppercase; margin-bottom: 10px;
        }

        .menu-card { border: 1px solid #e5e7eb; border-radius: 8px; margin-bottom: 10px; overflow: hidden; }
        .menu-name { background: #f9fafb; padding: 5px 10px; border-bottom: 1px solid #e5e7eb; font-weight: bold; }
        
        table { width: 100%; border-collapse: collapse; }
        th { background: #f3f4f6; font-size: 8px; padding: 5px; border: 1px solid #e5e7eb; text-transform: uppercase; }
        td { padding: 5px; border: 1px solid #e5e7eb; text-align: center; }

        /* Balance Estilo Dashboard */
        .balance-container { 
            background-color: #fefce8; padding: 10px; border-radius: 10px; 
            border: 1px solid #fef08a; margin-top: 5px;
        }
        .grid-nutrientes { width: 100%; }
        .nutriente-box { 
            background: white; border: 1px solid #e5e7eb; 
            padding: 4px; border-radius: 4px; text-align: center;
        }
        .label-nutriente { font-size: 7px; font-weight: bold; text-transform: uppercase; display: block; }
        .valor-nutriente { font-size: 9px; font-weight: bold; color: #111827; }
        .goal-nutriente { font-size: 7px; color: #9ca3af; }

        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 8px; color: #9ca3af; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Agenda Nutricional Semanal</h1>
        <p>Usuario: <strong>{{ $user->name }}</strong> | Período: {{ $rango }}</p>
    </div>

    @foreach($consumosSemanales as $fecha => $menuDays)
        <div class="dia-seccion">
            <div class="dia-titulo">
                {{ \Carbon\Carbon::parse($fecha)->translatedFormat('l, d \d\e F') }}
            </div>

            @php 
                $diaTotals = array_fill_keys(['calories','proteins','carbohydrates','fats','saturated_fat','trans_fat','polyunsaturated_fat','monounsaturated_fat','fiber','colesterol'], 0);
            @endphp

            @foreach($menuDays as $registro)
                <div class="menu-card">
                    <div class="menu-name">Menú: {{ $registro->menu->name }}</div>
                    <table>
                        <thead>
                            <tr>
                                <th style="text-align: left;">Plato</th>
                                <th>Kcal</th>
                                <th>Prot</th>
                                <th>Carbs</th>
                                <th>Grasas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $m = $registro->menu->getMacros(); @endphp
                            @foreach($registro->menu->platos as $plato)
                                <tr>
                                    <td style="text-align: left;">{{ $plato->name }} (x{{ $plato->pivot->quantity }})</td>
                                    <td>{{ round($m['calories'] ?? 0) }}</td>
                                    <td>{{ round($m['proteins'] ?? 0, 1) }}g</td>
                                    <td>{{ round($m['carbohydrates'] ?? 0, 1) }}g</td>
                                    <td>{{ round($m['fats'] ?? 0, 1) }}g</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @php
                    foreach($diaTotals as $key => $val) {
                        $diaTotals[$key] += $m[$key] ?? 0;
                    }
                @endphp
            @endforeach

            {{-- Balance del Día (Réplica de tu UI) --}}
            <div class="balance-container">
                <span style="font-weight: bold; font-size: 9px; margin-bottom: 5px; display: block;">BALANCE TOTAL DEL DÍA</span>
                <table style="border: none;">
                    <tr>
                        @foreach([
                            ['Kcal', 'calories'], ['Prot', 'proteins'], ['Carb', 'carbohydrates'], 
                            ['Fat', 'fats'], ['Sat', 'saturated_fat']
                        ] as $item)
                        <td style="border: none; padding: 2px;">
                            <div class="nutriente-box">
                                <span class="label-nutriente">{{ $item[0] }}</span>
                                <span class="valor-nutriente">{{ round($diaTotals[$item[1]], 1) }}</span>
                                <span class="goal-nutriente">/ {{ round($goals->{$item[1]} ?? 0) }}</span>
                            </div>
                        </td>
                        @endforeach
                    </tr>
                    <tr>
                        @foreach([
                            ['Fiber', 'fiber'], ['Trans', 'trans_fat'], ['Poly', 'polyunsaturated_fat'], 
                            ['Mono', 'monounsaturated_fat'], ['Colest', 'colesterol']
                        ] as $item)
                        <td style="border: none; padding: 2px;">
                            <div class="nutriente-box">
                                <span class="label-nutriente">{{ $item[0] }}</span>
                                <span class="valor-nutriente">{{ round($diaTotals[$item[1]], 1) }}</span>
                                <span class="goal-nutriente">/ {{ round($goals->{$item[1]} ?? 0) }}</span>
                            </div>
                        </td>
                        @endforeach
                    </tr>
                </table>
            </div>
        </div>
    @endforeach

    <div class="footer">
        Generado el {{ date('d/m/Y H:i') }} - Nutri-App Professional
    </div>
</body>
</html>