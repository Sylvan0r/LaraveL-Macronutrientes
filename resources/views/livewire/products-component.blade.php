<div> {{-- ÚNICO ELEMENTO RAÍZ --}}
    <div class="space-y-10 p-4">
        {{-- Notificación Toast --}}
        @if (session()->has('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" 
                 class="fixed bottom-10 right-10 z-[100] bg-yellow-500 text-white px-8 py-4 rounded-2xl shadow-2xl font-black animate-bounce">
                {{ session('success') }}
            </div>
        @endif

        {{-- HEADER --}}
        <div class="flex flex-col md:flex-row justify-between items-center bg-gray-800/40 p-10 rounded-[3rem] border border-gray-700/50 backdrop-blur-xl shadow-2xl">
            <div>
                <h2 class="text-5xl font-black text-white tracking-tighter uppercase">Mis <span class="text-yellow-400">Productos</span></h2>
                <p class="text-gray-400 text-lg font-medium">Gestiona tu despensa personal e ingredientes base.</p>
            </div>
            <button wire:click="openCreate" class="mt-6 md:mt-0 bg-yellow-500 hover:bg-yellow-400 text-gray-900 font-black px-12 py-4 rounded-2xl transition-all shadow-xl shadow-yellow-500/20 transform hover:scale-105 active:scale-95">
                + AÑADIR PRODUCTO
            </button>
        </div>

        {{-- GRID DE PRODUCTOS --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($userProducts as $product)
                <div class="group bg-gray-900/40 border border-gray-800 p-7 rounded-[2.5rem] hover:border-yellow-500/40 transition-all duration-500 flex flex-col shadow-xl backdrop-blur-sm">
                    
                    {{-- Header Tarjeta --}}
                    <div class="flex justify-between items-start mb-6">
                        <div class="max-w-[80%]">
                            <span class="text-[9px] font-black text-yellow-400 uppercase tracking-widest bg-yellow-500/10 px-3 py-1 rounded-lg border border-yellow-500/20">
                                {{ $product->category->name ?? 'Ingrediente' }}
                            </span>
                            <h3 class="text-2xl font-black text-white truncate mt-3 tracking-tight">{{ $product->name }}</h3>
                        </div>
                        <button wire:click="toggleFavorite({{ $product->id }})" class="text-2xl transition-all hover:scale-125 {{ $product->is_favorite ? 'text-yellow-400' : 'text-gray-700' }}">
                            {{ $product->is_favorite ? '★' : '☆' }}
                        </button>
                    </div>

                    {{-- GRID NUTRICIONAL UNIFORME (10 ATRIBUTOS) --}}
                    <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-2 xl:grid-cols-2 gap-2 flex-1">
                        @php
                            $displayFields = [
                                ['label' => 'Calorias', 'value' => $product->calories, 'unit' => 'kcal', 'color' => 'text-yellow-400'],
                                ['label' => 'Proteína', 'value' => $product->proteins, 'unit' => 'g', 'color' => 'text-indigo-400'],
                                ['label' => 'Carbos', 'value' => $product->carbohydrates, 'unit' => 'g', 'color' => 'text-emerald-400'],
                                ['label' => 'Grasa T.', 'value' => $product->total_fat, 'unit' => 'g', 'color' => 'text-orange-400'],
                                ['label' => 'Sat.', 'value' => $product->saturated_fat, 'unit' => 'g', 'color' => 'text-rose-400'],
                                ['label' => 'Trans', 'value' => $product->trans_fat, 'unit' => 'g', 'color' => 'text-rose-500'],
                                ['label' => 'Mono.', 'value' => $product->monounsaturated_fat, 'unit' => 'g', 'color' => 'text-pink-400'],
                                ['label' => 'Poli.', 'value' => $product->polyunsaturated_fat, 'unit' => 'g', 'color' => 'text-pink-500'],
                                ['label' => 'Fibra', 'value' => $product->fiber, 'unit' => 'g', 'color' => 'text-sky-400'],
                                ['label' => 'Colest.', 'value' => $product->colesterol, 'unit' => 'mg', 'color' => 'text-orange-200'],
                            ];
                        @endphp

                        @foreach($displayFields as $field)
                            <div class="bg-gray-800/40 p-2.5 rounded-xl border border-gray-700/30 flex flex-col justify-center items-center group-hover:bg-gray-800/60 transition-colors">
                                <span class="text-[7px] font-black {{ $field['color'] }} uppercase tracking-tighter mb-0.5">{{ $field['label'] }}</span>
                                <div class="flex items-baseline gap-0.5">
                                    <span class="text-sm font-black text-white">
                                        {{ number_format($field['value'], ($field['unit'] == 'kcal' || $field['unit'] == 'mg' ? 0 : 1)) }}
                                    </span>
                                    <span class="text-[8px] font-bold text-gray-600 uppercase">{{ $field['unit'] }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Footer Tarjeta --}}
                    <div class="flex justify-between items-center mt-6 pt-4 border-t border-gray-800">
                        <span class="text-[9px] text-gray-600 font-bold uppercase tracking-widest italic">Por 100g</span>
                        <button wire:click="deleteProduct({{ $product->id }})" class="text-gray-700 hover:text-rose-500 font-black text-[9px] uppercase transition-all tracking-widest">Eliminar</button>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-24 bg-gray-900/20 border-2 border-dashed border-gray-700 rounded-[3rem] text-center">
                    <p class="text-gray-600 font-black uppercase tracking-widest">No hay productos en tu despensa</p>
                </div>
            @endforelse
        </div>

        {{-- MODAL CREAR PRODUCTO --}}
        @if($showCreate)
            <div class="fixed inset-0 z-[150] flex items-center justify-center p-4 backdrop-blur-2xl bg-black/80">
                <div class="bg-gray-900 border border-gray-800 rounded-[3rem] w-full max-w-5xl max-h-[90vh] overflow-hidden flex flex-col shadow-2xl animate-zoom-in">
                    
                    <div class="p-8 border-b border-gray-800 flex justify-between items-center bg-gray-800/20">
                        <h4 class="text-3xl font-black text-white uppercase tracking-tighter">Nuevo <span class="text-yellow-400">Producto</span></h4>
                        <button wire:click="closeCreate" class="w-12 h-12 bg-gray-800 hover:bg-rose-500 text-white rounded-full transition-all text-xl">✕</button>
                    </div>

                    <form wire:submit.prevent="createProduct" class="p-10 overflow-y-auto custom-scrollbar">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                            <div class="md:col-span-2">
                                <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest ml-2 block mb-2">Nombre del Producto *</label>
                                <input wire:model.defer="name" placeholder="Ej: Pechuga de Pollo" class="w-full bg-gray-800 border-gray-700 rounded-2xl p-4 text-white focus:ring-2 focus:ring-yellow-500 font-bold outline-none">
                            </div>

                            <div>
                                <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest ml-2 block mb-2">Categoría *</label>
                                <select wire:model="category_id" class="w-full bg-gray-800 border-gray-700 rounded-2xl p-4 text-white font-bold outline-none">
                                    <option value="" selected hidden>Selecciona categoría</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" class="bg-gray-900">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Inputs de Nutrientes (Los 10 atributos) --}}
                        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                            @php
                                $inputFields = [
                                    'calories' => ['Calorías', 'kcal', 'text-yellow-400'],
                                    'proteins' => ['Proteínas', 'g', 'text-indigo-400'],
                                    'carbohydrates' => ['Carbos', 'g', 'text-emerald-400'],
                                    'total_fat' => ['Grasa Total', 'g', 'text-orange-400'],
                                    'saturated_fat' => ['Saturadas', 'g', 'text-rose-400'],
                                    'trans_fat' => ['Trans', 'g', 'text-rose-500'],
                                    'monounsaturated_fat' => ['Mono.', 'g', 'text-pink-400'],
                                    'polyunsaturated_fat' => ['Poli.', 'g', 'text-pink-500'],
                                    'fiber' => ['Fibra', 'g', 'text-sky-400'],
                                    'colesterol' => ['Colesterol', 'mg', 'text-orange-200'],
                                ];
                            @endphp

                            @foreach($inputFields as $key => $info)
                                <div class="bg-gray-800/20 p-4 rounded-2xl border border-gray-800/60">
                                    <label class="text-[9px] font-black text-gray-500 uppercase tracking-widest block mb-2 text-center {{ $info[2] }}">{{ $info[0] }}</label>
                                    <input wire:model.defer="{{ $key }}" type="number" step="any" placeholder="0.0" class="w-full bg-transparent border-none p-0 text-center text-white font-black text-xl focus:ring-0 outline-none">
                                    <span class="block text-[8px] font-black text-gray-700 uppercase text-center mt-1">{{ $info[1] }}</span>
                                </div>
                            @endforeach
                        </div>

                        <div class="flex justify-end gap-6 pt-10 mt-10 border-t border-gray-800">
                            <button type="button" wire:click="closeCreate" class="font-black text-gray-500 uppercase tracking-widest text-xs hover:text-white transition-colors">Cancelar</button>
                            <button type="submit" class="bg-yellow-500 hover:bg-yellow-400 text-gray-900 font-black uppercase tracking-widest text-xs px-12 py-5 rounded-2xl shadow-xl shadow-yellow-500/10 transition-all transform hover:scale-105 active:scale-95">
                                Registrar Producto
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>

    <style>
        @keyframes zoom-in { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }
        .animate-zoom-in { animation: zoom-in 0.2s ease-out; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #374151; border-radius: 10px; }
    </style>
</div>