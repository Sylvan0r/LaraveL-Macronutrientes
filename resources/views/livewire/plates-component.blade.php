<div>
    <div class="space-y-10 p-4">
        {{-- Notificación Toast --}}
        @if (session()->has('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" 
                 class="fixed bottom-10 right-10 z-[200] bg-yellow-500 text-gray-900 px-8 py-4 rounded-2xl shadow-2xl font-black animate-bounce border-2 border-yellow-600">
                {{ session('success') }}
            </div>
        @endif

        {{-- HEADER --}}
        <div class="flex flex-col md:flex-row justify-between items-center bg-gray-800/40 p-10 rounded-[3rem] border border-gray-700/50 backdrop-blur-xl shadow-2xl">
            <div>
                <h2 class="text-5xl font-black text-white tracking-tighter uppercase">Mis <span class="text-yellow-400">Platos</span></h2>
                <p class="text-gray-400 text-lg font-medium">Crea combinaciones perfectas de ingredientes.</p>
            </div>
            <button wire:click="openCreate" class="mt-6 md:mt-0 bg-yellow-500 hover:bg-yellow-400 text-gray-900 font-black px-12 py-4 rounded-2xl transition-all shadow-xl shadow-yellow-500/20 transform hover:scale-105 active:scale-95">
                + NUEVO PLATO
            </button>
        </div>

        {{-- GRID DE PLATOS GUARDADOS --}}
        <div class="grid grid-cols-1 gap-6">
            @forelse($userPlates as $plato)
                @php $m = $plato->getMacros(); @endphp
                <div class="group bg-gray-900/40 border border-gray-800 p-8 rounded-[3rem] hover:border-yellow-500/40 transition-all duration-500 flex flex-col lg:flex-row gap-8 shadow-xl backdrop-blur-sm">
                    
                    {{-- Info Básica --}}
                    <div class="lg:w-1/3 border-b lg:border-b-0 lg:border-r border-gray-800 pb-6 lg:pb-0 lg:pr-8 flex flex-col justify-between">
                        <div>
                            <div class="flex justify-between items-start">
                                <h3 class="text-3xl font-black text-white leading-tight uppercase tracking-tighter">{{ $plato->name }}</h3>
                                <button wire:click="toggleFavorite({{ $plato->id }})" class="text-2xl transition-all hover:scale-125 {{ $plato->is_favorite ? 'text-yellow-400' : 'text-gray-700' }}">
                                    {{ $plato->is_favorite ? '★' : '☆' }}
                                </button>
                            </div>
                            <p class="text-gray-500 text-sm mt-2 italic">{{ $plato->descripcion ?? 'Receta personalizada.' }}</p>
                        </div>
                        <div class="mt-6 flex flex-wrap gap-2">
                            @foreach($plato->products as $p)
                                <span class="bg-gray-800 text-[9px] font-black text-gray-400 px-3 py-1 rounded-full border border-gray-700 uppercase">
                                    {{ $p->name }} <span class="text-yellow-500">{{ $p->pivot->quantity }}g</span>
                                </span>
                            @endforeach
                        </div>
                        @can('eliminar platos')
                            <button wire:click="deletePlate({{ $plato->id }})" class="mt-6 text-gray-700 hover:text-rose-500 font-black text-[9px] uppercase tracking-widest transition-all text-left">Eliminar Plato</button>
                        @endcan
                    </div>

                    {{-- Grid 10 Atributos Uniforme --}}
                    <div class="lg:w-2/3 grid grid-cols-2 sm:grid-cols-5 gap-3">
                        @php
                            $plateFields = [
                                ['label' => 'Calorías', 'val' => $m['calories'], 'u' => 'kcal', 'c' => 'text-yellow-400'],
                                ['label' => 'Proteína', 'val' => $m['proteins'], 'u' => 'g', 'c' => 'text-indigo-400'],
                                ['label' => 'Carbos', 'val' => $m['carbohydrates'], 'u' => 'g', 'c' => 'text-emerald-400'],
                                ['label' => 'Grasa T.', 'val' => $m['total_fat'], 'u' => 'g', 'c' => 'text-orange-400'],
                                ['label' => 'Sat.', 'val' => $m['saturated_fat'], 'u' => 'g', 'c' => 'text-rose-400'],
                                ['label' => 'Trans', 'val' => $m['trans_fat'], 'u' => 'g', 'c' => 'text-rose-500'],
                                ['label' => 'Mono.', 'val' => $m['monounsaturated_fat'], 'u' => 'g', 'c' => 'text-pink-400'],
                                ['label' => 'Poli.', 'val' => $m['polyunsaturated_fat'], 'u' => 'g', 'c' => 'text-pink-500'],
                                ['label' => 'Fibra', 'val' => $m['fiber'], 'u' => 'g', 'c' => 'text-sky-400'],
                                ['label' => 'Colest.', 'val' => $m['colesterol'], 'u' => 'mg', 'c' => 'text-orange-200'],
                            ];
                        @endphp
                        @foreach($plateFields as $f)
                            <div class="bg-gray-800/40 p-3 rounded-2xl border border-gray-700/30 flex flex-col items-center justify-center">
                                <span class="text-[7px] font-black {{ $f['c'] }} uppercase mb-1">{{ $f['label'] }}</span>
                                <div class="flex items-baseline gap-0.5">
                                    <span class="text-lg font-black text-white">{{ number_format($f['val'], 1) }}</span>
                                    <span class="text-[8px] font-bold text-gray-600 uppercase">{{ $f['u'] }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="py-24 bg-gray-900/20 border-2 border-dashed border-gray-700 rounded-[3rem] text-center">
                    <p class="text-gray-600 font-black uppercase tracking-widest italic text-xl">No has guardado platos aún</p>
                </div>
            @endforelse
        </div>

        {{-- MODAL CREACIÓN DE PLATO --}}
        @if($showCreate)
            <div class="fixed inset-0 z-[150] flex items-center justify-center p-4 backdrop-blur-2xl bg-black/80">
                <div class="bg-gray-950 border border-gray-800 rounded-[3.5rem] w-full max-w-[98vw] h-[92vh] overflow-hidden flex flex-col shadow-2xl animate-zoom-in">
                    
                    {{-- Header Modal --}}
                    <div class="p-8 border-b border-gray-900 flex justify-between items-center bg-gray-900/50">
                        <div class="w-1/2">
                            <input wire:model.defer="name" placeholder="NOMBRE DEL NUEVO PLATO" class="bg-transparent border-b-4 border-yellow-500 text-4xl font-black text-white outline-none w-full placeholder:text-gray-800">
                        </div>
                        <button wire:click="closeCreate" class="w-14 h-14 bg-gray-800 hover:bg-rose-500 text-white rounded-full transition-all text-2xl">✕</button>
                    </div>

                    <div class="flex-1 flex overflow-hidden">
                        {{-- LISTA 1: PRIVADOS --}}
                        <div class="w-1/4 border-r border-gray-900 flex flex-col p-6">
                            <h5 class="text-yellow-500 font-black uppercase tracking-tighter mb-6 text-center text-sm">Mis Productos</h5>
                            <div class="flex-1 overflow-y-auto space-y-3 custom-scrollbar pr-2">
                                @foreach($myProducts as $prod)
                                    <div class="bg-gray-900 border border-gray-800 p-4 rounded-[2rem] hover:border-yellow-500/30 transition-all">
                                        <div class="flex justify-between items-start mb-3 px-2">
                                            <span class="font-black text-xs text-white uppercase truncate">{{ $prod->name }}</span>
                                            <span class="text-lg {{ $prod->is_favorite ? 'text-yellow-400' : 'text-gray-800' }}">★</span>
                                        </div>
                                        <div class="flex gap-2">
                                            <input type="number" wire:model.defer="tempQuantities.{{ $prod->id }}" placeholder="g" class="w-full bg-black border border-gray-800 rounded-xl px-3 py-2 text-yellow-500 font-black outline-none text-sm">
                                            <button wire:click="addProduct({{ $prod->id }})" class="bg-yellow-500 text-black font-black px-4 rounded-xl hover:bg-yellow-400">+</button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- LISTA 2: COMUNIDAD --}}
                        <div class="w-1/4 border-r border-gray-900 flex flex-col p-6 bg-black/10">
                            <h5 class="text-indigo-500 font-black uppercase tracking-tighter mb-6 text-center text-sm">Comunidad</h5>
                            <div class="flex-1 overflow-y-auto space-y-3 custom-scrollbar pr-2">
                                @foreach($publicProducts as $prod)
                                    <div class="bg-gray-900 border border-gray-800 p-4 rounded-[2rem] hover:border-indigo-500/30 transition-all">
                                        <div class="flex justify-between items-start mb-3 px-2">
                                            <span class="font-black text-xs text-white uppercase truncate">{{ $prod->name }}</span>
                                            <span class="text-lg {{ $prod->is_favorite ? 'text-yellow-400' : 'text-gray-800' }}">★</span>
                                        </div>
                                        <div class="flex gap-2">
                                            <input type="number" wire:model.defer="tempQuantities.{{ $prod->id }}" placeholder="g" class="w-full bg-black border border-gray-800 rounded-xl px-3 py-2 text-indigo-400 font-black outline-none text-sm">
                                            <button wire:click="addProduct({{ $prod->id }})" class="bg-indigo-500 text-white font-black px-4 rounded-xl hover:bg-indigo-400">+</button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- COL 3: SELECCIONADOS CON SUS 10 ATRIBUTOS --}}
                        <div class="w-2/4 flex flex-col p-8 bg-black/40">
                            <div class="flex-1 overflow-y-auto space-y-6 custom-scrollbar pr-4">
                                <h6 class="text-center text-gray-700 font-black uppercase tracking-[0.4em] mb-6 text-[10px]">Ingredientes en el plato</h6>
                                @foreach($selectedIngredients as $index => $item)
                                    <div class="bg-gray-900 border border-gray-800 p-6 rounded-[2.5rem] relative group">
                                        <div class="flex justify-between items-center mb-4">
                                            <div>
                                                <span class="text-xl font-black text-white uppercase tracking-tighter">{{ $item['name'] }}</span>
                                                <span class="ml-3 text-yellow-500 font-black text-xs italic">{{ $item['quantity'] }}g</span>
                                            </div>
                                            <button wire:click="removeIngredient({{ $index }})" class="text-gray-700 hover:text-rose-500 transition-all">✕</button>
                                        </div>
                                        
                                        {{-- GRID NUTRICIONAL DEL INGREDIENTE AÑADIDO --}}
                                        <div class="grid grid-cols-5 gap-2">
                                            @php
                                                $fields = [
                                                    ['L' => 'CAL', 'V' => $item['calories'], 'C' => 'text-yellow-400'],
                                                    ['L' => 'PRO', 'V' => $item['proteins'], 'C' => 'text-indigo-400'],
                                                    ['L' => 'CAR', 'V' => $item['carbohydrates'], 'C' => 'text-emerald-400'],
                                                    ['L' => 'GRA', 'V' => $item['total_fat'], 'C' => 'text-orange-400'],
                                                    ['L' => 'SAT', 'V' => $item['saturated_fat'], 'C' => 'text-rose-400'],
                                                    ['L' => 'TRA', 'V' => $item['trans_fat'], 'C' => 'text-rose-600'],
                                                    ['L' => 'MON', 'V' => $item['monounsaturated_fat'], 'C' => 'text-pink-400'],
                                                    ['L' => 'POL', 'V' => $item['polyunsaturated_fat'], 'C' => 'text-pink-600'],
                                                    ['L' => 'FIB', 'V' => $item['fiber'], 'C' => 'text-sky-400'],
                                                    ['L' => 'COL', 'V' => $item['colesterol'], 'C' => 'text-orange-200'],
                                                ];
                                            @endphp
                                            @foreach($fields as $f)
                                                <div class="bg-black p-2 rounded-xl text-center border border-gray-800">
                                                    <span class="block text-[6px] font-black {{ $f['C'] }} uppercase">{{ $f['L'] }}</span>
                                                    <span class="text-[10px] font-black text-white italic">{{ number_format($f['V'], 1) }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            <div class="mt-8 border-t border-gray-900 pt-8 flex justify-center">
                                <button wire:click="createPlato" class="w-full bg-yellow-500 hover:bg-yellow-400 text-black font-black py-8 rounded-[2.5rem] text-3xl shadow-2xl transition-all active:scale-95">
                                    GUARDAR RECETA
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <style>
        @keyframes zoom-in { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }
        .animate-zoom-in { animation: zoom-in 0.3s cubic-bezier(0.16, 1, 0.3, 1); }
        .custom-scrollbar::-webkit-scrollbar { width: 5px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #1f2937; border-radius: 10px; }
    </style>
</div>