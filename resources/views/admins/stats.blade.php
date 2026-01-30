@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Estadísticas de Uso</h1>
    <ul>
        <li>Productos creados: {{ $productos }}</li>
        <li>Platos creados: {{ $platos }}</li>
        <li>Menus creados: {{ $menus }}</li>
    </ul>
</div>
@endsection