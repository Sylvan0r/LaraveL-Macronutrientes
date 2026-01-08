<div class="space-y-10">
    {{-- Notificación Toast --}}
    @if (session()->has('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" 
             class="fixed bottom-10 right-10 z-[100] bg-emerald-500 text-white px-8 py-4 rounded-2xl shadow-2xl font-black animate-bounce">
            {{ session('success') }}
        </div>
    @endif

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row justify-between items-center bg-gray-800/40 p-10 rounded-[3rem] border border-gray-700/50 backdrop-blur-xl">
        <div>
            <h2 class="text-5xl font-black text-white tracking-tighter uppercase">Mis <span class="text-emerald-400">Productos</span></h2>
            <p class="text-gray-400 text-lg font-medium">Gestiona tu despensa personal e ingredientes.</p>
        </div>
        <button wire:click="openCreate" class="mt-6 md:mt-0 bg-emerald-500 hover:bg-emerald-400 text-white font-black px-12 py-4 rounded-2xl transition-all shadow-xl shadow-emerald-500/20 active:scale-95">
            + AÑADIR PRODUCTO
        </button>
    </div>

    {{-- GRID DE PRODUCTOS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($userProducts as $product)
            <div class="group bg-gray-800/60 border border-gray-700 p-6 rounded-[2.5rem] hover:border-emerald-500/30 transition-all duration-500 relative flex flex-col shadow-lg">
                
                <div class="flex justify-between items-start mb-4">
                    <div class="max-w-[80%]">
                        <span class="text-[10px] font-black text-emerald-400 uppercase tracking-widest">{{ $product->category->name ?? 'Sin Categoría' }}</span>
                        <h3 class="text-xl font-black text-white truncate">{{ $product->name }}</h3>
                    </div>
                    {{-- Estrella arriba a la derecha --}}
                    <button wire:click="toggleFavorite({{ $product->id }})" class="text-2xl transition-all hover:scale-125 {{ $product->is_favorite ? 'text-yellow-400' : 'text-gray-600' }}">
                        {{ $product->is_favorite ? '★' : '☆' }}
                    </button>
                </div>

                {{-- Macros Rápidos --}}
                <div class="grid grid-cols-3 gap-2 py-4 border-y border-gray-700/50 my-2">
                    <div class="text-center">
                        <span class="block text-[9px] text-gray-500 uppercase font-bold">Calorías</span>
                        <span class="text-sm font-black text-white">{{ $product->calories ?? 0 }}</span>
                    </div>
                    <div class="text-center">
                        <span class="block text-[9px] text-gray-500 uppercase font-bold">Proteína</span>
                        <span class="text-sm font-black text-white">{{ $product->proteins ?? 0 }}g</span>
                    </div>
                    <div class="text-center">
                        <span class="block text-[9px] text-gray-500 uppercase font-bold">Carbs</span>
                        <span class="text-sm font-black text-white">{{ $product->carbohydrates ?? 0 }}g</span>
                    </div>
                </div>

                {{-- Botón Eliminar abajo (Separado de la estrella) --}}
                <div class="mt-4 flex justify-end">
                    <button wire:click="deleteProduct({{ $product->id }})" class="opacity-0 group-hover:opacity-100 transition-opacity text-rose-500/50 hover:text-rose-500 font-black text-[10px] uppercase tracking-widest">
                        Eliminar Producto
                    </button>
                </div>
            </div>
        @empty
            <div class="col-span-full py-20 bg-gray-800/20 border-2 border-dashed border-gray-700 rounded-[3rem] text-center">
                <p class="text-gray-600 font-black uppercase tracking-widest">Tu despensa está vacía</p>
            </div>
        @endforelse
    </div>

    {{-- MODAL CREAR PRODUCTO --}}
    @if($showCreate)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 backdrop-blur-2xl bg-black/60">
            <div class="bg-gray-900 border border-gray-800 rounded-[3rem] w-full max-w-4xl max-h-[90vh] overflow-hidden flex flex-col shadow-2xl animate-zoom-in">
                
                <div class="p-8 border-b border-gray-800 flex justify-between items-center bg-gray-800/20">
                    <h4 class="text-3xl font-black text-white uppercase tracking-tighter">Nuevo <span class="text-emerald-400">Producto</span></h4>
                    <button wire:click="closeCreate" class="w-12 h-12 bg-gray-800 hover:bg-rose-500 text-white rounded-full transition-all text-xl">✕</button>
                </div>

                <form wire:submit.prevent="createProduct" class="p-10 overflow-y-auto space-y-8 custom-scrollbar">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest ml-2">Nombre del Producto *</label>
                            <input wire:model.defer="name" placeholder="Ej: Pechuga de Pollo Orgánica" class="w-full bg-gray-800 border-gray-700 rounded-2xl p-4 text-white focus:ring-2 focus:ring-emerald-500 font-bold">
                        </div>

                        <div>
                            <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest ml-2">Categoría</label>
                            <select wire:model.defer="category_id" class="w-full bg-gray-800 border-gray-700 rounded-2xl p-4 text-white focus:ring-2 focus:ring-emerald-500 font-bold">
                                <option value="">Selecciona categoría</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Grid de Macros --}}
                        @foreach(['calories' => 'Calorías (kcal)', 'proteins' => 'Proteínas (g)', 'carbohydrates' => 'Carbohidratos (g)', 'total_fat' => 'Grasas Totales (g)', 'saturated_fat' => 'Grasas Saturadas (g)', 'fiber' => 'Fibra', 'colesterol'] as $key => $label)
                            <div>
                                <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest ml-2">{{ $label }}</label>
                                <input wire:model.defer="{{ $key }}" type="number" step="any" placeholder="0.00" class="w-full bg-gray-800 border-gray-700 rounded-2xl p-4 text-white focus:ring-2 focus:ring-emerald-500 font-bold">
                            </div>
                        @endforeach
                    </div>

                    <div class="flex justify-end gap-6 pt-6 border-t border-gray-800">
                        <button type="button" wire:click="closeCreate" class="font-black text-gray-500 uppercase tracking-widest text-xs hover:text-white transition-colors">Cancelar</button>
                        <button type="submit" class="bg-emerald-600 hover:bg-emerald-500 text-white font-black uppercase tracking-widest text-xs px-12 py-5 rounded-2xl shadow-xl shadow-emerald-500/20">
                            Guardar Producto
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>