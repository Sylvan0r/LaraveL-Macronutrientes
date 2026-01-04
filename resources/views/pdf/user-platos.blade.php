<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Mis Platos</title>
        <style>
            body {
                font-family: DejaVu Sans, sans-serif;
                font-size: 12px;
            }
            h1 {
                text-align: center;
                margin-bottom: 20px;
            }
            .plato {
                border: 1px solid #ccc;
                padding: 10px;
                margin-bottom: 10px;
            }
            .plato h3 {
                margin: 0 0 5px 0;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 5px;
            }
            th, td {
                border: 1px solid #aaa;
                padding: 4px;
                text-align: left;
            }
        </style>
    </head>
    <body>
        <h1>Platos creados por {{ $user->name }}</h1>
        @foreach($platos as $plato)
            <div class="plato">
                <h3>{{ $plato->name }}</h3>
                <p>{{ $plato->descripcion }}</p>

                <table>
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Cal</th>
                            <th>Prot</th>
                            <th>Carbs</th>
                            <th>Grasas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($plato->products as $product)
                            <tr>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->calories }}</td>
                                <td>{{ $product->proteins }}</td>
                                <td>{{ $product->carbohydrates }}</td>
                                <td>{{ $product->total_fat }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach
    </body>
</html>