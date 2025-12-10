<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ __('Dashboard') }}
            </h2>
            <p class="text-gray-500 mt-2 md:mt-0">Bienvenido, {{ auth()->user()->name }}</p>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Panel Agregar Producto --}}
                <div class="bg-white shadow-lg rounded-xl p-6 flex flex-col justify-between">
                    <h3 class="text-xl font-bold mb-4 text-gray-700">Agregar Producto</h3>
                    <p class="text-gray-500 mb-4">
                        Añade alimentos a tu agenda y registra sus valores nutricionales.
                    </p>
                    <div class="mt-auto">
                        @livewire('products-component')
                    </div>
                </div>

                {{-- Lista de productos --}}
                <div class="lg:col-span-2 bg-white shadow-lg rounded-xl p-6 flex flex-col">
                    <h3 class="text-xl font-bold mb-4 text-gray-700">Tus Productos</h3>

                    @php
                        $userProducts = \App\Models\Product::where('id_user', auth()->id())->get();
                        $useScroll = $userProducts->count() > 3;
                    @endphp

                    <div class="{{ $useScroll ? 'overflow-y-auto max-h-60' : '' }} border rounded-lg">
                        <ul class="divide-y divide-gray-200">
                            @foreach($userProducts as $product)
                                <li class="flex justify-between items-center px-4 py-3 hover:bg-gray-50">
                                    <div>
                                        <p class="font-medium text-gray-700">{{ $product->name }}</p>
                                        <p class="text-gray-500 text-sm">
                                            {{ $product->category->name ?? 'Sin categoría' }} |
                                            {{ $product->calories ?? 0 }} kcal |
                                            Proteínas: {{ $product->proteins ?? 0 }} g
                                        </p>
                                    </div>
                                    <button wire:click.prevent="$emit('confirmDelete', {{ $product->id }})"
                                            class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded shadow text-sm">
                                        Eliminar
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    @if(session()->has('message'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mt-4 rounded" role="alert">
                            {{ session('message') }}
                        </div>
                    @endif
                </div>

                {{-- Panel Agregar Plato --}}
                <div class="bg-white shadow-lg rounded-xl p-6 flex flex-col justify-between">
                    <h3 class="text-xl font-bold mb-4 text-gray-700">Agregar Plato</h3>
                    <p class="text-gray-500 mb-4">
                        ¡Crea un plato usando productos públicos o tus productos personales!
                    </p>
                    <div class="mt-auto">
                        @livewire('plates-component')
                    </div>
                </div>

                {{-- Lista de platos --}}
                <div class="lg:col-span-2 bg-white shadow-lg rounded-xl p-6 flex flex-col">
                    <h3 class="text-xl font-bold mb-4 text-gray-700">Tus Platos</h3>

                    @php
                        $userPlates = \App\Models\Plato::where('user_id', auth()->id())->with('products.category')->get();
                        $useScroll = $userPlates->count() > 3;
                    @endphp

                    <div class="{{ $useScroll ? 'overflow-y-auto max-h-80' : '' }} border rounded-lg">
                        <ul class="divide-y divide-gray-200">
                            @foreach($userPlates as $plato)
                                <li class="flex flex-col md:flex-row justify-between items-start md:items-center px-4 py-3 hover:bg-gray-50">
                                    <div class="flex-1">
                                        {{-- Nombre del plato --}}
                                        <p class="font-semibold text-gray-800">{{ $plato->name }}</p>
                                        
                                        {{-- Descripción --}}
                                        @if($plato->descripcion)
                                            <p class="text-gray-600 text-sm mb-1">{{ $plato->descripcion }}</p>
                                        @endif
                                        
                                        {{-- Productos del plato --}}
                                        @if($plato->products->count())
                                            <p class="text-gray-500 text-sm">
                                                @foreach($plato->products as $prod)
                                                    {{ $prod->name }} ({{ $prod->category->name ?? 'Sin categoría' }}) - {{ $prod->pivot->quantity }}
                                                    @if(!$loop->last), @endif
                                                @endforeach
                                            </p>
                                        @endif
                                    </div>

                                    {{-- Botón eliminar --}}
                                    <button wire:click="deletePlato({{ $plato->id }})"
                                            class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded shadow text-sm mt-2 md:mt-0">
                                        Eliminar
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>


                {{-- Panel Agregar Menú --}}
                <div class="bg-white shadow-lg rounded-xl p-6 flex flex-col justify-between">
                    <h3 class="text-xl font-bold mb-4 text-gray-700">Crear Menú</h3>
                    <p class="text-gray-500 mb-4">
                        ¡Crea un menú a partir de tus platos creados!
                    </p>
                    <div class="mt-auto">
                        @livewire('plates-component')
                    </div>
                </div>

               {{-- Lista de platos --}}
                <div class="lg:col-span-2 bg-white shadow-lg rounded-xl p-6 flex flex-col">
                    <h3 class="text-xl font-bold mb-4 text-gray-700">Tus Menus</h3>

                    @php
                        $userPlates = \App\Models\Plato::where('user_id', auth()->id())->with('products.category')->get();
                        $useScroll = $userPlates->count() > 3;
                    @endphp

                    <div class="{{ $useScroll ? 'overflow-y-auto max-h-80' : '' }} border rounded-lg">
                        <ul class="divide-y divide-gray-200">
                            @foreach($userPlates as $plato)
                                <li class="flex flex-col md:flex-row justify-between items-start md:items-center px-4 py-3 hover:bg-gray-50">
                                    <div class="flex-1">
                                        {{-- Nombre del plato --}}
                                        <p class="font-semibold text-gray-800">{{ $plato->name }}</p>
                                        
                                        {{-- Descripción --}}
                                        @if($plato->descripcion)
                                            <p class="text-gray-600 text-sm mb-1">{{ $plato->descripcion }}</p>
                                        @endif
                                        
                                        {{-- Productos del plato --}}
                                        @if($plato->products->count())
                                            <p class="text-gray-500 text-sm">
                                                @foreach($plato->products as $prod)
                                                    {{ $prod->name }} ({{ $prod->category->name ?? 'Sin categoría' }}) - {{ $prod->pivot->quantity }}
                                                    @if(!$loop->last), @endif
                                                @endforeach
                                            </p>
                                        @endif
                                    </div>

                                    {{-- Botón eliminar --}}
                                    <button wire:click="deletePlato({{ $plato->id }})"
                                            class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded shadow text-sm mt-2 md:mt-0">
                                        Eliminar
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>