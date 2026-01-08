<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Informe Nutricional - {{ $user->name }}</title>
        <style>
            @page { margin: 1cm; }
            body {
                font-family: 'Helvetica', 'Arial', sans-serif;
                font-size: 11px;
                color: #333;
                line-height: 1.5;
                background-color: #fff;
            }
            /* Header del PDF */
            .header {
                border-bottom: 3px solid #fbbf24; /* Amarillo del dashboard */
                padding-bottom: 10px;
                margin-bottom: 30px;
            }
            .header h1 {
                margin: 0;
                font-size: 24px;
                text-transform: uppercase;
                color: #1f2937;
            }
            .header p {
                margin: 0;
                color: #6b7280;
                font-size: 12px;
            }
            /* Tarjeta de Plato */
            .plato-container {
                margin-bottom: 25px;
                page-break-inside: avoid; /* Evita que un plato se corte entre páginas */
                border: 1px solid #e5e7eb;
                border-radius: 8px;
                overflow: hidden;
            }
            .plato-header {
                background-color: #f9fafb;
                padding: 10px 15px;
                border-bottom: 1px solid #e5e7eb;
            }
            .plato-name {
                font-size: 16px;
                font-weight: bold;
                color: #111827;
                margin: 0;
            }
            .plato-desc {
                font-size: 10px;
                color: #4b5563;
                margin: 2px 0 0 0;
            }
            /* Tabla de Nutrientes */
            table {
                width: 100%;
                border-collapse: collapse;
            }
            th {
                background-color: #1f2937;
                color: #ffffff;
                font-weight: bold;
                text-transform: uppercase;
                font-size: 9px;
                letter-spacing: 0.5px;
                padding: 8px;
            }
            td {
                padding: 8px;
                border-bottom: 1px solid #f3f4f6;
            }
            .text-center { text-align: center; }
            .font-bold { font-weight: bold; }
            
            /* Badge de Calorías */
            .cal-badge {
                color: #b45309;
                font-weight: bold;
            }
            
            /* Footer de página */
            .footer {
                position: fixed;
                bottom: 0;
                width: 100%;
                text-align: center;
                font-size: 9px;
                color: #9ca3af;
                border-top: 1px solid #e5e7eb;
                padding-top: 5px;
            }
        </style>
    </head>
    
    <body>
        <div class="header">
            <h1>Agenda de Macronutrientes</h1>
            <p>Usuario: <strong>{{ $user->name }}</strong> | Fecha de generación: {{ date('d/m/Y') }}</p>
        </div>

        @foreach($platos as $plato)
            <div class="plato-container">
                <div class="plato-header">
                    <h3 class="plato-name">{{ $plato->name }}</h3>
                    @if($plato->descripcion)
                        <p class="plato-desc">{{ $plato->descripcion }}</p>
                    @endif
                </div>

                <table>
                    <thead>
                        <tr>
                            <th style="text-align: left; width: 40%;">Producto</th>
                            <th class="text-center">Energía (Cal)</th>
                            <th class="text-center">Prot (g)</th>
                            <th class="text-center">Carbs (g)</th>
                            <th class="text-center">Grasas (g)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php 
                            $tCal = 0; $tProt = 0; $tCarb = 0; $tFat = 0; 
                        @endphp
                        @foreach($plato->products as $product)
                            <tr>
                                <td>{{ $product->name }}</td>
                                <td class="text-center cal-badge">{{ number_format($product->calories, 0) }}</td>
                                <td class="text-center">{{ number_format($product->proteins, 1) }}</td>
                                <td class="text-center">{{ number_format($product->carbohydrates, 1) }}</td>
                                <td class="text-center">{{ number_format($product->total_fat, 1) }}</td>
                            </tr>
                            @php
                                $tCal += $product->calories;
                                $tProt += $product->proteins;
                                $tCarb += $product->carbohydrates;
                                $tFat += $product->total_fat;
                            @endphp
                        @endforeach
                    </tbody>
                    {{-- Fila de Totales del Plato --}}
                    <tfoot style="background-color: #fefce8;">
                        <tr>
                            <td class="font-bold" style="text-align: right;">TOTAL PLATO:</td>
                            <td class="text-center font-bold cal-badge">{{ number_format($tCal, 0) }}</td>
                            <td class="text-center font-bold">{{ number_format($tProt, 1) }}</td>
                            <td class="text-center font-bold">{{ number_format($tCarb, 1) }}</td>
                            <td class="text-center font-bold">{{ number_format($tFat, 1) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endforeach

        <div class="footer">
            Generado automáticamente por el Sistema de Gestión Nutricional - Página <span class="pagenum"></span>
        </div>
    </body>
</html>