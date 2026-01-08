<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center bg-gray-800/50 backdrop-blur-md p-6 rounded-2xl border border-gray-700 shadow-xl">
            <div>
                <h2 class="font-extrabold text-4xl text-yellow-400 tracking-tight">
                    Agenda de <span class="text-white">Macronutrientes</span>
                </h2>
                <p class="text-gray-400 text-sm mt-1">Controla tu progreso y optimiza tu alimentación</p>
            </div>
            <div class="mt-4 md:mt-0 px-4 py-2 bg-gray-700/50 rounded-full border border-gray-600">
                <p class="text-gray-200 font-medium">✨ Bienvenido, <span class="text-yellow-400">{{ auth()->user()->name }}</span></p>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-900 min-h-screen text-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">

            {{-- FILA 1: ACCIONES RÁPIDAS --}}
            <div class="flex justify-between items-center">
                <h3 class="text-xl font-semibold text-gray-300">Resumen Diario</h3>
                <a href="{{ route('pdf.user.platos') }}"
                   class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-500 text-white font-bold px-6 py-2.5 rounded-xl transition-all duration-300 shadow-lg hover:shadow-red-900/40 group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="Group 17 7l-5 5m0 0l-5-5m5 5V3" />
                    </svg>
                    Descargar PDF
                </a>
            </div>

            {{-- FILA 2: GRID PRINCIPAL --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

                {{-- IZQUIERDA: GESTIÓN (Componentes de datos) --}}
                <div class="lg:col-span-8 space-y-8">
                    <div class="bg-gray-800/40 p-1 rounded-3xl border border-gray-700/50 shadow-inner">
                        {{-- Contenedores para Livewire con padding interno --}}
                        <div class="space-y-6 p-4">
                            <div class="bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-700">
                                @livewire('products-component')
                            </div>
                        </div>
                    </div>
                </div>

                {{-- DERECHA: CALENDARIO (Sticky para que siga el scroll) --}}
                <div class="lg:col-span-4 sticky top-8">
                    <div class="bg-gray-800 border border-yellow-500/30 rounded-3xl shadow-2xl overflow-hidden">
                        <div class="bg-yellow-500 p-4">
                            <h4 class="text-gray-900 font-bold text-center uppercase tracking-widest text-sm">Planificación Semanal</h4>
                        </div>
                        <div class="p-4">
                            @livewire('menu-calendar-component')
                        </div>
                    </div>
                </div>

            </div>

            {{-- FILA 3: OBJETIVOS NUTRICIONALES DETALLADOS --}}
            <div class="bg-gradient-to-r from-gray-800 to-gray-900 rounded-3xl p-8 border border-gray-700 shadow-2xl">
                <div class="flex items-center gap-4 mb-6">
                    <div class="p-3 bg-yellow-400 rounded-lg text-gray-900">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold">Metas y Objetivos</h3>
                </div>
                @livewire('nutritional-goals-component')
            </div>
        </div>
    </div>
</x-app-layout>