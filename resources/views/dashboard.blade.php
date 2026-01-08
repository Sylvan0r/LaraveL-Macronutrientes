<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center bg-gray-900 p-4 rounded-lg shadow">
            <h2 class="font-bold text-3xl text-yellow-400">Agenda de Macronutrientes</h2>
            <p class="text-gray-200 mt-2 md:mt-0">Bienvenido, {{ auth()->user()->name }}</p>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-900 min-h-screen text-gray-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- FILA 1: BOTÓN PDF + OBJETIVO NUTRICIONAL --}}
            <div class="flex justify-end space-x-4">
                {{-- Botón PDF --}}
                <a href="{{ route('pdf.user.platos') }}"
                   class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded shadow text-sm">
                    Descargar PDF de mis platos
                </a>
            </div>

            {{-- FILA 2: GRID PRINCIPAL --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

                {{-- IZQUIERDA: PRODUCTOS / PLATOS / MENÚS --}}
                <div class="lg:col-span-2 flex flex-col gap-6">
                    @livewire('products-component')
                    @livewire('plates-component')
                    @livewire('menus-component')
                </div>

                {{-- DERECHA: CALENDARIO --}}
                <div class="lg:col-span-1 flex flex-col h-full">
                    <div class="flex-1">
                        @livewire('menu-calendar-component')
                    </div>
                </div>

            </div>

            {{-- FILA 3: OBJETIVOS NUTRICIONALES DETALLADOS --}}
            <div>
                @livewire('nutritional-goals-component')
            </div>
        </div>
    </div>
</x-app-layout>