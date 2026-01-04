<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center bg-gray-900 p-4 rounded-lg shadow">
            <h2 class="font-bold text-3xl text-yellow-400 leading-tight">Agenda de Macronutrientes</h2>
            <p class="text-gray-200 mt-2 md:mt-0">Bienvenido, {{ auth()->user()->name }}</p>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-900 min-h-screen text-gray-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-3 gap-6">
            <a href="{{ route('pdf.user.platos') }}"
                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded shadow align-middle text-center lg:col-span-3 block">
                Descargar PDF de mis platos
            </a>
            {{-- Panel Agregar Producto + Lista --}}
            <div class="lg:col-span-3">
                @livewire('products-component')
            </div>

            {{-- Panel Agregar Plato + Lista --}}
            <div class="lg:col-span-3">
                @livewire('plates-component')
            </div>

            {{-- Panel Agregar Menú + Lista --}}
            <div class="lg:col-span-3">
                @livewire('menus-component')
            </div>
        </div>
    </div>
</x-app-layout>
