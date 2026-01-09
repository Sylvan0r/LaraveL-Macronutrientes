<div class="space-y-10 p-4">
    @if (session()->has('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" 
             class="fixed bottom-10 right-10 z-[100] bg-yellow-400 text-gray-900 px-8 py-4 rounded-3xl shadow-2xl font-black animate-bounce">
            {{ session('success') }}
        </div>
    @endif

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row justify-between items-center bg-gray-800/40 p-10 rounded-[3rem] border border-gray-700/50 backdrop-blur-xl shadow-2xl">
        <div>
            <h2 class="text-5xl font-black text-white tracking-tighter uppercase">Mis <span class="text-yellow-400">Menús</span></h2>
            <p class="text-gray-400 font-medium text-lg">Agrupa tus platos para organizar tu semana.</p>
        </div>
        <button wire:click="openCreate" class="mt-6 md:mt-0 bg-yellow-400 hover:bg-yellow-300 text-gray-900 font-black px-10 py-4 rounded-2xl transition-all shadow-xl shadow-yellow-400/10">
            + NUEVO MENÚ
        </button>
    </div>

    {{-- GRID DE MENÚS --}}
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
        @forelse($userMenus as $menu)
            @php $m = $menu->getMacros(); @endphp
            <div class="bg-gray-800/80 border border-gray-700 p-8 rounded-[2.5rem] flex flex-col justify-between shadow-lg">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h3 class="text-3xl font-black text-white uppercase tracking-tighter">{{ $menu->name }}</h3>
                        <p class="text-gray-500 font-bold text-[10px] uppercase mt-2 italic">
                            {{ $menu->platos->count() }} Platos incluidos
                        </p>
                    </div>
                    <button onclick="confirm('¿Eliminar menú?') || event.stopImmediatePropagation()" wire:click="deleteMenu({{ $menu->id }})" class="p-2 text-gray-600 hover:text-rose-500 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    </button>
                </div>

                <div class="flex flex-wrap gap-2 mb-8">
                    @foreach($menu->platos as $plato)
                        <div class="bg-gray-900/50 px-4 py-2 rounded-xl border border-gray-700/50 flex items-center gap-2">
                            <span class="text-gray-300 font-bold text-xs">{{ $plato->name }}</span>
                            <span class="text-[10px] font-black text-indigo-400 uppercase">x{{ $plato->pivot->quantity }}</span>
                        </div>
                    @endforeach
                </div>

                {{-- Dashboard Nutricional --}}
                <div class="grid grid-cols-2 md:grid-cols-5 gap-2 pt-6 border-t border-gray-700/50">
                    @php
                        $fields = [
                            ['l' => 'Kcal', 'v' => $m['calories'], 'c' => 'text-yellow-400'],
                            ['l' => 'Prot', 'v' => $m['proteins'], 'c' => 'text-indigo-400'],
                            ['l' => 'Carb', 'v' => $m['carbohydrates'], 'c' => 'text-emerald-400'],
                            ['l' => 'Fat', 'v' => $m['total_fat'], 'c' => 'text-orange-400'],
                            ['l' => 'Sat', 'v' => $m['saturated_fat'], 'c' => 'text-rose-400'],
                            ['l' => 'Trans', 'v' => $m['trans_fat'], 'c' => 'text-rose-600'],
                            ['l' => 'Poly', 'v' => $m['polyunsaturated_fat'], 'c' => 'text-pink-400'],
                            ['l' => 'Mono', 'v' => $m['monounsaturated_fat'], 'c' => 'text-pink-600'],
                            ['l' => 'Fibra', 'v' => $m['fiber'], 'c' => 'text-sky-400'],
                            ['l' => 'Colest', 'v' => $m['colesterol'], 'c' => 'text-orange-200'],
                        ];
                    @endphp
                    @foreach($fields as $f)
                        <div class="text-center bg-gray-900/50 p-2 rounded-xl border border-gray-700/30">
                            <span class="block text-[7px] {{ $f['c'] }} font-black uppercase">{{ $f['l'] }}</span>
                            <span class="text-xs font-black text-white">{{ number_format($f['v'], 1) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="col-span-full py-20 bg-gray-800/20 border-2 border-dashed border-gray-700 rounded-[3rem] text-center italic text-gray-600">
                No hay menús guardados.
            </div>
        @endforelse
    </div>

    {{-- MODAL --}}
    @if($showCreate)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 backdrop-blur-2xl bg-black/70">
            <div class="bg-gray-900 border border-gray-800 rounded-[3rem] w-full max-w-5xl max-h-[90vh] overflow-hidden flex flex-col shadow-2xl animate-zoom-in">
                <div class="p-8 border-b border-gray-800 flex justify-between items-center">
                    <h4 class="text-3xl font-black text-white uppercase">Nuevo <span class="text-yellow-400">Menú</span></h4>
                    <button wire:click="closeCreate" class="w-12 h-12 bg-gray-800 hover:bg-rose-500 text-white rounded-full transition-all">✕</button>
                </div>

                <div class="p-10 overflow-y-auto space-y-8 custom-scrollbar">
                    <div>
                        <label class="text-xs font-black text-gray-500 uppercase ml-2">Nombre del Menú</label>
                        <input wire:model.defer="name" placeholder="Ej: Dieta Lunes a Miércoles" class="w-full bg-gray-800 border-gray-700 rounded-2xl p-4 text-white font-bold mt-2">
                        @error('name') <span class="text-rose-500 text-[10px] font-black mt-1 ml-2">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-4">
                        <label class="text-xs font-black text-gray-500 uppercase ml-2">Selecciona tus Platos</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($userPlatos as $plato)
                                <div class="flex items-center justify-between p-4 bg-gray-800 rounded-2xl border border-gray-700">
                                    <div class="flex items-center gap-3">
                                        <input type="checkbox" wire:model="selectedPlatos" value="{{ $plato->id }}" class="w-6 h-6 rounded bg-black border-gray-600 text-yellow-400">
                                        <span class="font-bold text-gray-200">{{ $plato->name }}</span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <input type="number" wire:model.defer="quantities.{{ $plato->id }}" placeholder="1" class="w-14 bg-black border-none rounded-lg p-2 text-center text-white text-xs font-black">
                                        <button type="button" wire:click="togglePlatoFavorite({{ $plato->id }})" class="text-lg {{ $plato->is_favorite ? 'text-yellow-400' : 'text-gray-700' }}">★</button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @error('selectedPlatos') <span class="text-rose-500 text-[10px] font-black mt-1 ml-2 block italic">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end gap-4 pt-6">
                        <button wire:click="createMenu" class="bg-indigo-600 hover:bg-indigo-500 text-white font-black uppercase tracking-widest text-xs px-12 py-5 rounded-2xl transition-transform active:scale-95">
                            GUARDAR MENÚ
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>