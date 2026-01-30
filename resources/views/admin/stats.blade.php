<x-app-layout>
    <div class="space-y-10 p-6 bg-gray-900 min-h-screen text-white">
        
        {{-- HEADER AL ESTILO "MIS PLATOS" --}}
        <div class="flex flex-col md:flex-row justify-between items-center bg-gray-800/40 p-10 rounded-[3rem] border border-gray-700/50 backdrop-blur-xl shadow-2xl">
            <div>
                <h2 class="text-5xl font-black text-white tracking-tighter uppercase">
                    Panel de <span class="text-yellow-400">Control</span>
                </h2>
                <p class="text-gray-400 text-lg font-medium">Estadísticas globales de la plataforma.</p>
            </div>
            <div class="mt-6 md:mt-0 bg-gray-900/50 px-8 py-4 rounded-2xl border border-gray-700 shadow-inner">
                <span class="text-gray-500 font-black text-xs uppercase tracking-widest">Estado del Sistema</span>
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 bg-emerald-500 rounded-full animate-pulse"></div>
                    <span class="text-emerald-400 font-bold uppercase text-sm">Online</span>
                </div>
            </div>
        </div>

        {{-- GRID DE ESTADÍSTICAS --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            
            {{-- Card: Productos --}}
            <div class="group bg-gray-900/40 border border-gray-800 p-8 rounded-[3rem] hover:border-blue-500/40 transition-all duration-500 shadow-xl backdrop-blur-sm relative overflow-hidden">
                <div class="absolute top-0 right-0 p-6 opacity-10 group-hover:opacity-20 transition-opacity">
                    <svg class="w-20 h-20 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
                <h3 class="text-gray-500 font-black text-xs uppercase tracking-[0.3em] mb-4">Total Productos</h3>
                <div class="flex items-baseline gap-4">
                    <span class="text-6xl font-black text-white tracking-tighter">{{ $productos }}</span>
                    <span class="text-blue-500 font-bold uppercase text-xs">Productos</span>
                </div>
                <div class="mt-6 h-1 w-full bg-gray-800 rounded-full overflow-hidden">
                    <div class="bg-blue-500 h-full w-2/3 shadow-[0_0_15px_rgba(59,130,246,0.5)]"></div>
                </div>
            </div>

            {{-- Card: Platos --}}
            <div class="group bg-gray-900/40 border border-gray-800 p-8 rounded-[3rem] hover:border-yellow-500/40 transition-all duration-500 shadow-xl backdrop-blur-sm relative overflow-hidden">
                <div class="absolute top-0 right-0 p-6 opacity-10 group-hover:opacity-20 transition-opacity">
                    <svg class="w-20 h-20 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
                <h3 class="text-gray-500 font-black text-xs uppercase tracking-[0.3em] mb-4">Platos Creados</h3>
                <div class="flex items-baseline gap-4">
                    <span class="text-6xl font-black text-white tracking-tighter">{{ $platos }}</span>
                    <span class="text-yellow-500 font-bold uppercase text-xs">Platos</span>
                </div>
                <div class="mt-6 h-1 w-full bg-gray-800 rounded-full overflow-hidden">
                    <div class="bg-yellow-500 h-full w-1/2 shadow-[0_0_15px_rgba(234,179,8,0.5)]"></div>
                </div>
            </div>

            {{-- Card: Menus --}}
            <div class="group bg-gray-900/40 border border-gray-800 p-8 rounded-[3rem] hover:border-purple-500/40 transition-all duration-500 shadow-xl backdrop-blur-sm relative overflow-hidden">
                <div class="absolute top-0 right-0 p-6 opacity-10 group-hover:opacity-20 transition-opacity">
                    <svg class="w-20 h-20 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                </div>
                <h3 class="text-gray-500 font-black text-xs uppercase tracking-[0.3em] mb-4">Planes de Menú</h3>
                <div class="flex items-baseline gap-4">
                    <span class="text-6xl font-black text-white tracking-tighter">{{ $menus }}</span>
                    <span class="text-purple-500 font-bold uppercase text-xs">Menus</span>
                </div>
                <div class="mt-6 h-1 w-full bg-gray-800 rounded-full overflow-hidden">
                    <div class="bg-purple-500 h-full w-1/3 shadow-[0_0_15px_rgba(168,85,247,0.5)]"></div>
                </div>
            </div>

        </div>

        {{-- PIE DE PÁGINA O NOTA --}}
        <div class="text-center">
            <p class="text-[10px] font-black text-gray-700 uppercase tracking-[0.5em]">Actualizado en tiempo real • Sistema Macronutrientes v2.0</p>
        </div>
    </div>
</x-app-layout>