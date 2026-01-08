<div class="space-y-10">
    {{-- Notificación Toast --}}
    @if (session()->has('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" 
             class="fixed bottom-10 right-10 z-[100] bg-yellow-400 text-gray-900 px-8 py-4 rounded-2xl shadow-2xl font-black animate-bounce">
            {{ session('success') }}
        </div>
    @endif

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row justify-between items-center bg-gray-800/40 p-10 rounded-[3rem] border border-gray-700/50 backdrop-blur-xl">
        <div>
            <h2 class="text-5xl font-black text-white tracking-tighter uppercase">Mis <span class="text-yellow-400">Menús</span></h2>
            <p class="text-gray-400 text-lg font-medium">Agrupa tus platos para organizar tu semana.</p>
        </div>
        <button wire:click="openCreate" class="mt-6 md:mt-0 bg-yellow-400 hover:bg-yellow-300 text-gray-900 font-black px-12 py-4 rounded-2xl transition-all shadow-xl shadow-yellow-400/20 active:scale-95">
            + NUEVO MENÚ
        </button>
    </div>

    {{-- GRID DE MENÚS --}}
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
        @forelse($userMenus as $menu)
            @php $macros = $menu->getMacros(); @endphp
            <div class="group bg-gray-800/80 border border-gray-700 p-8 rounded-[3rem] hover:border-yellow-400/30 transition-all duration-500 relative flex flex-col shadow-xl">
                
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h3 class="text-3xl font-black text-white group-hover:text-yellow-400 transition-colors uppercase tracking-tighter">{{ $menu->name }}</h3>
                        <p class="text-gray-500 font-bold text-sm uppercase tracking-widest mt-1">{{ $menu->platos->count() }} Platos incluidos</p>
                    </div>
                    <button wire:click="deleteMenu({{ $menu->id }})" class="text-gray-600 hover:text-rose-500 transition-colors p-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>

                {{-- Listado de Platos dentro del Menú --}}
                <div class="space-y-3 mb-8">
                    @foreach($menu->platos as $plato)
                        <div class="flex items-center justify-between bg-gray-900/50 p-4 rounded-2xl border border-gray-700/30">
                            <div class="flex items-center gap-3">
                                <button wire:click="togglePlatoFavorite({{ $plato->id }})" class="{{ $plato->is_favorite ? 'text-yellow-400' : 'text-gray-700' }} transition-colors">★</button>
                                <span class="text-gray-200 font-bold">{{ $plato->name }}</span>
                            </div>
                            <span class="text-gray-500 font-black text-xs px-3 py-1 bg-gray-800 rounded-lg">x{{ $plato->pivot->quantity }}</span>
                        </div>
                    @endforeach
                </div>

                {{-- Resumen Nutricional del Menú --}}
                <div class="grid grid-cols-4 gap-3 pt-6 border-t border-gray-700/50">
                    <div class="text-center bg-yellow-400/5 p-3 rounded-2xl border border-yellow-400/10">
                        <span class="block text-[10px] text-yellow-500 font-black uppercase">Calorías</span>
                        <span class="text-lg font-black text-white">{{ round($macros['kcal']) }}</span>
                    </div>
                    <div class="text-center bg-indigo-500/5 p-3 rounded-2xl border border-indigo-500/10">
                        <span class="block text-[10px] text-indigo-400 font-black uppercase">Prot</span>
                        <span class="text-lg font-black text-white">{{ round($macros['prot']) }}g</span>
                    </div>
                    <div class="text-center bg-emerald-500/5 p-3 rounded-2xl border border-emerald-500/10">
                        <span class="block text-[10px] text-emerald-400 font-black uppercase">Carbs</span>
                        <span class="text-lg font-black text-white">{{ round($macros['carbs']) }}g</span>
                    </div>
                    <div class="text-center bg-orange-500/5 p-3 rounded-2xl border border-orange-500/10">
                        <span class="block text-[10px] text-orange-400 font-black uppercase">Fat</span>
                        <span class="text-lg font-black text-white">{{ round($macros['fat']) }}g</span>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-20 bg-gray-800/20 border-2 border-dashed border-gray-700 rounded-[3rem] text-center">
                <p class="text-gray-600 font-black uppercase tracking-widest">No has diseñado menús todavía</p>
            </div>
        @endforelse
    </div>

    {{-- MODAL CREAR MENÚ --}}
    @if($showCreate)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 backdrop-blur-2xl bg-black/60">
            <div class="bg-gray-900 border border-gray-800 rounded-[3rem] w-full max-w-4xl max-h-[90vh] overflow-hidden flex flex-col shadow-2xl animate-zoom-in">
                
                <div class="p-8 border-b border-gray-800 flex justify-between items-center bg-gray-800/20">
                    <h4 class="text-3xl font-black text-white uppercase tracking-tighter">Crear <span class="text-yellow-400">Nuevo Menú</span></h4>
                    <button wire:click="closeCreate" class="w-12 h-12 bg-gray-800 hover:bg-rose-500 text-white rounded-full transition-all">✕</button>
                </div>

                <form wire:submit.prevent="createMenu" class="p-10 overflow-y-auto space-y-10 custom-scrollbar">
                    <div class="space-y-4">
                        <label class="text-xs font-black text-gray-500 uppercase tracking-widest ml-2">Nombre del Menú</label>
                        <input wire:model.defer="name" placeholder="Ej: Definición Semana 1" class="w-full bg-gray-800 border-gray-700 rounded-2xl p-5 text-white focus:ring-2 focus:ring-yellow-400 font-bold text-lg">
                    </div>

                    <div class="space-y-4">
                        <label class="text-xs font-black text-gray-500 uppercase tracking-widest ml-2">Selecciona tus Platos</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-h-80 overflow-y-auto pr-2 custom-scrollbar">
                            @foreach($userPlatos as $plato)
                                <div class="flex items-center justify-between p-4 bg-gray-800/50 rounded-2xl border transition-all {{ $plato->is_favorite ? 'border-yellow-400/40 shadow-[0_0_15px_rgba(250,204,21,0.05)]' : 'border-gray-700' }} group hover:border-yellow-400/50">
                                    <div class="flex items-center gap-4">
                                        <input type="checkbox" wire:model="selectedPlatos" value="{{ $plato->id }}" class="w-6 h-6 rounded-lg bg-gray-900 border-gray-700 text-yellow-400 focus:ring-0">
                                        <div class="flex flex-col">
                                            <span class="font-bold text-gray-200">{{ $plato->name }}</span>
                                            @if($plato->is_favorite)
                                                <span class="text-[9px] text-yellow-500 font-black uppercase">Favorito</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        <div class="flex flex-col items-center">
                                            <span class="text-[9px] font-black text-gray-500 mb-1 uppercase tracking-tighter">Cant.</span>
                                            <input type="number" wire:model.defer="quantities.{{ $plato->id }}" placeholder="1" class="w-14 bg-gray-950 border-none rounded-xl p-2 text-center text-white font-black">
                                        </div>
                                        
                                        {{-- ESTRELLA INTERACTIVA --}}
                                        <button type="button" 
                                                wire:click.stop="togglePlatoFavorite({{ $plato->id }})" 
                                                class="text-2xl transition-all transform hover:scale-110 {{ $plato->is_favorite ? 'text-yellow-400' : 'text-gray-600 hover:text-gray-400' }}">
                                            {{ $plato->is_favorite ? '★' : '☆' }}
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex justify-end gap-6 pt-6 border-t border-gray-800">
                        <button type="button" wire:click="closeCreate" class="font-black text-gray-500 uppercase tracking-widest text-xs hover:text-white transition-colors">Cancelar</button>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white font-black uppercase tracking-widest text-xs px-12 py-5 rounded-2xl shadow-xl shadow-indigo-500/20">
                            Confirmar Menú
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #374151; border-radius: 10px; }
    </style>
</div>