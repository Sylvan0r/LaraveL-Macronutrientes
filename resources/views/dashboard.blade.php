<x-app-layout>
    <x-slot name="header">
        {{-- Header con efecto Neumórfico Dark --}}
        <div class="relative overflow-hidden bg-gray-800/40 backdrop-blur-xl p-8 rounded-[2.5rem] border border-gray-700/50 shadow-2xl">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-yellow-400/10 rounded-full blur-3xl"></div>
            
            <div class="flex flex-col md:flex-row justify-between items-center relative z-10">
                <div>
                    <h2 class="font-black text-5xl text-white tracking-tighter uppercase">
                        Mi <span class="text-yellow-400 font-black">Agenda</span>
                    </h2>
                    <p class="text-gray-400 text-lg font-medium mt-1">Sincroniza tus macros con tu entrenamiento</p>
                </div>
                
                <div class="mt-6 md:mt-0 flex items-center gap-4 bg-gray-900/50 px-6 py-3 rounded-2xl border border-gray-700">
                    <div class="w-10 h-10 bg-yellow-400 rounded-full flex items-center justify-center font-black text-gray-900 shadow-lg shadow-yellow-400/20">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-500 uppercase font-black tracking-widest">Usuario Activo</p>
                        <p class="text-white font-bold leading-none">{{ auth()->user()->name }}</p>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-10 bg-gray-950 min-h-screen text-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">

            {{-- SECCIÓN: RESUMEN Y ACCIÓN --}}
            <div class="flex flex-col md:flex-row justify-between items-end gap-6">
                <div>
                    <span class="text-yellow-400 font-black text-xs uppercase tracking-[0.3em]">Dashboard</span>
                    <h3 class="text-3xl font-black text-white uppercase tracking-tighter">Resumen <span class="text-gray-500 text-2xl italic">Estratégico</span></h3>
                </div>

                @role('admin')
                    <a href="{{ route('admin.stats') }}" class="btn btn-primary">Ver Estadísticas</a>
                @endrole
            </div>

            {{-- GRID PRINCIPAL --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
                
                {{-- IZQUIERDA: METAS NUTRICIONALES (Principal) --}}
                <div class="lg:col-span-8 space-y-10">
                    <div class="bg-gray-800/40 border border-gray-700/50 rounded-[3rem] p-10 backdrop-blur-sm relative overflow-hidden group">
                        <div class="absolute top-0 left-0 w-2 h-full bg-yellow-400 transition-all group-hover:w-3"></div>
                        
                        <div class="flex items-center gap-6 mb-10">
                            <div class="p-4 bg-gray-900 rounded-3xl border border-gray-700 shadow-inner">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-3xl font-black uppercase tracking-tighter">Metas <span class="text-yellow-400">Diarias</span></h3>
                                <p class="text-gray-500 font-medium italic">Tu progreso en tiempo real basado en el calendario</p>
                            </div>
                        </div>

                        @livewire('nutritional-goals-component')
                    </div>
                </div>

                {{-- DERECHA: CALENDARIO (Sticky) --}}
                <div class="lg:col-span-4 lg:sticky lg:top-10 h-fit">
                    <div class="bg-gray-800 rounded-[3rem] border border-gray-700 shadow-2xl overflow-hidden group transition-all hover:border-yellow-400/30">
                        <div class="bg-yellow-400 p-6 flex justify-between items-center">
                            <h4 class="text-gray-900 font-black uppercase tracking-widest text-sm italic">Cronograma</h4>
                            <span class="bg-gray-900 text-white text-[10px] px-3 py-1 rounded-full font-black uppercase tracking-tighter">7 Días</span>
                        </div>
                        <div class="p-6 bg-gradient-to-b from-gray-800 to-gray-900">
                            @livewire('menu-calendar-component')
                        </div>
                    </div>

                    {{-- Mini Tip Card --}}
                    <div class="mt-6 bg-indigo-600 rounded-[2.5rem] p-8 relative overflow-hidden group">
                        <div class="relative z-10">
                            <h5 class="text-white font-black uppercase text-xs tracking-widest mb-2 opacity-70">Tip de hoy</h5>
                            <p class="text-white text-lg font-bold leading-tight group-hover:scale-105 transition-transform origin-left">"La constancia vence a la perfección. Mantén tus macros estables."</p>
                        </div>
                        <svg class="absolute -right-4 -bottom-4 h-24 w-24 text-indigo-500 rotate-12 group-hover:rotate-0 transition-all duration-700" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M13 2L3 14h9v8l10-12h-9l1 1-9z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- FILA INFERIOR: PRODUCTOS Y PLATOS (Opcional, si quieres accesos directos) --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-10 border-t border-gray-800/50">
                <a href="{{ route('mis-platos') }}" class="bg-gray-800/20 border border-gray-700 p-8 rounded-3xl hover:bg-gray-800 transition-all text-center group">
                    <span class="block text-4xl mb-2 group-hover:scale-125 transition-transform">🍲</span>
                    <span class="block text-white font-black uppercase text-sm tracking-widest">Mis Platos</span>
                </a>
                <a href="{{ route('mis-menus') }}" class="bg-gray-800/20 border border-gray-700 p-8 rounded-3xl hover:bg-gray-800 transition-all text-center group">
                    <span class="block text-4xl mb-2 group-hover:scale-125 transition-transform">📅</span>
                    <span class="block text-white font-black uppercase text-sm tracking-widest">Mis Menús</span>
                </a>
                <a href="{{ route('mis-productos') }}" class="bg-gray-800/20 border border-gray-700 p-8 rounded-3xl hover:bg-gray-800 transition-all text-center group">
                    <span class="block text-4xl mb-2 group-hover:scale-125 transition-transform">🍎</span>
                    <span class="block text-white font-black uppercase text-sm tracking-widest">Productos</span>
                </a>
            </div>

        </div>
    </div>
</x-app-layout>