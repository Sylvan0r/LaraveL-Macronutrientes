<div class="space-y-8 p-4">
    {{-- Toast de Notificación --}}
    @if (session()->has('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" 
             class="fixed bottom-10 right-10 z-[100] bg-indigo-600 text-white px-8 py-4 rounded-3xl shadow-2xl animate-bounce font-bold">
            {{ session('success') }}
        </div>
    @endif

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row justify-between items-center bg-gray-800/40 p-10 rounded-[3rem] border border-gray-700/50 backdrop-blur-xl shadow-2xl">
        <div>
            <h2 class="text-5xl font-black text-white tracking-tighter">MIS <span class="text-yellow-400">RECETAS</span></h2>
            <p class="text-gray-400 font-medium text-lg">Crea platos combinando tus ingredientes favoritos.</p>
        </div>
        <button wire:click="openCreate" class="mt-6 md:mt-0 bg-yellow-400 hover:bg-yellow-300 text-gray-900 font-black px-10 py-4 rounded-2xl transition-all transform hover:scale-105 active:scale-95 shadow-xl shadow-yellow-400/10">
            + NUEVO PLATO
        </button>
    </div>

    {{-- GRID --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($userPlates as $plato)
            @php $macros = $plato->getMacros(); @endphp
            <div class="group bg-gray-800/80 border border-gray-700 p-7 rounded-[2.5rem] hover:border-yellow-400/50 transition-all duration-500 relative flex flex-col justify-between shadow-lg hover:shadow-yellow-400/5">
                
                <div class="flex justify-between items-start">
                    <div class="max-w-[80%]">
                        <h3 class="text-2xl font-black text-white leading-tight mb-2 truncate">{{ $plato->name }}</h3>
                        <p class="text-gray-500 text-sm line-clamp-2 leading-relaxed">{{ $plato->descripcion ?? 'Sin descripción añadida.' }}</p>
                    </div>
                    {{-- Estrella arriba a la derecha --}}
                    <button wire:click="toggleFavorite({{ $plato->id }})" class="text-2xl transition-all hover:scale-125 {{ $plato->is_favorite ? 'text-yellow-400' : 'text-gray-600' }}">
                        {{ $plato->is_favorite ? '★' : '☆' }}
                    </button>
                </div>

                {{-- Macros Dashboard --}}
                <div class="grid grid-cols-3 gap-3 mt-8 pt-6 border-t border-gray-700/50">
                    <div class="text-center bg-gray-900/50 p-3 rounded-2xl border border-gray-700/50">
                        <span class="block text-[10px] text-indigo-400 font-black uppercase tracking-widest">Prot</span>
                        <span class="text-xl font-black text-white">{{ round($macros['prot']) }}g</span>
                    </div>
                    <div class="text-center bg-gray-900/50 p-3 rounded-2xl border border-gray-700/50">
                        <span class="block text-[10px] text-emerald-400 font-black uppercase tracking-widest">Carb</span>
                        <span class="text-xl font-black text-white">{{ round($macros['carbs']) }}g</span>
                    </div>
                    <div class="text-center bg-gray-900/50 p-3 rounded-2xl border border-gray-700/50">
                        <span class="block text-[10px] text-orange-400 font-black uppercase tracking-widest">Fat</span>
                        <span class="text-xl font-black text-white">{{ round($macros['fat']) }}g</span>
                    </div>
                </div>

                {{-- Eliminar abajo a la derecha, lejos de la estrella --}}
                <div class="mt-4 flex justify-end">
                    <button wire:click="deletePlate({{ $plato->id }})" class="opacity-0 group-hover:opacity-100 transition-opacity p-2 text-rose-500/40 hover:text-rose-500 font-bold text-[10px] uppercase tracking-tighter">
                        ELIMINAR PLATO
                    </button>
                </div>
            </div>
        @empty
            <div class="col-span-full py-20 bg-gray-800/20 border-2 border-dashed border-gray-700 rounded-[3rem] text-center">
                <p class="text-gray-600 font-bold uppercase tracking-widest">No hay platos registrados</p>
            </div>
        @endforelse
    </div>

    {{-- MODAL --}}
    @if($showCreate)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 backdrop-blur-2xl bg-black/70">
            <div class="bg-gray-900 border border-gray-800 rounded-[3rem] w-full max-w-5xl max-h-[90vh] overflow-hidden flex flex-col shadow-2xl">
                <div class="p-8 border-b border-gray-800 flex justify-between items-center bg-gray-800/20">
                    <h4 class="text-3xl font-black text-white uppercase tracking-tighter">Configurar <span class="text-yellow-400">Receta</span></h4>
                    <button wire:click="closeCreate" class="w-10 h-10 bg-gray-800 hover:bg-rose-500 text-white rounded-full transition-all">✕</button>
                </div>

                <form wire:submit.prevent="createPlato" class="p-10 overflow-y-auto space-y-10 custom-scrollbar">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                        {{-- Detalles --}}
                        <div class="space-y-6">
                            <div class="space-y-4">
                                <label class="text-xs font-black text-gray-500 uppercase tracking-widest">Datos básicos</label>
                                <input wire:model.defer="name" placeholder="Nombre (ej. Cena Keto)" class="w-full bg-gray-800/50 border-gray-700 rounded-2xl p-4 text-white focus:ring-2 focus:ring-yellow-400 transition-all font-bold">
                                <textarea wire:model.defer="descripcion" placeholder="Instrucciones o descripción..." rows="4" class="w-full bg-gray-800/50 border-gray-700 rounded-2xl p-4 text-white focus:ring-2 focus:ring-yellow-400 transition-all"></textarea>
                            </div>
                        </div>

                        {{-- Lista de Productos con Favoritos --}}
                        <div class="space-y-6">
                            <label class="text-xs font-black text-gray-500 uppercase tracking-widest">Seleccionar Ingredientes</label>
                            <div class="space-y-4 max-h-[400px] overflow-y-auto pr-4 custom-scrollbar">
                                {{-- Sección Mis Productos --}}
                                <div class="space-y-2">
                                    <span class="text-[10px] text-indigo-400 font-black uppercase">Muestrario Personal</span>
                                    @foreach($userProducts as $product)
                                        <div class="flex items-center justify-between p-3 bg-gray-800 rounded-2xl border border-gray-700">
                                            <div class="flex items-center gap-3">
                                                <input type="checkbox" wire:model="selectedProducts" value="{{ $product->id }}" class="w-5 h-5 rounded-lg bg-gray-950 border-gray-700 text-yellow-400 focus:ring-0">
                                                <span class="font-bold text-gray-200 text-sm">{{ $product->name }}</span>
                                            </div>
                                            <div class="flex items-center gap-3">
                                                <input type="number" wire:model.defer="quantities.{{ $product->id }}" placeholder="g" class="w-16 bg-gray-950 border-none rounded-xl text-xs p-2 text-center text-white font-bold">
                                                <button type="button" wire:click="toggleFavoriteProduct({{ $product->id }})" class="text-lg {{ $product->is_favorite ? 'text-yellow-400' : 'text-gray-600 hover:text-gray-400' }}">
                                                    {{ $product->is_favorite ? '★' : '☆' }}
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                {{-- Sección Públicos --}}
                                <div class="space-y-2 mt-6">
                                    <span class="text-[10px] text-emerald-400 font-black uppercase">Ingredientes Base</span>
                                    @foreach($publicProducts as $product)
                                        <div class="flex items-center justify-between p-3 bg-gray-800/30 rounded-2xl border border-gray-700/30">
                                            <div class="flex items-center gap-3">
                                                <input type="checkbox" wire:model="selectedProducts" value="{{ $product->id }}" class="w-5 h-5 rounded-lg bg-gray-950 border-gray-700 text-yellow-400 focus:ring-0">
                                                <span class="font-bold text-gray-300 text-sm">{{ $product->name }}</span>
                                            </div>
                                            <div class="flex items-center gap-3">
                                                <input type="number" wire:model.defer="quantities.{{ $product->id }}" placeholder="g" class="w-16 bg-gray-950 border-none rounded-xl text-xs p-2 text-center text-white">
                                                <button type="button" wire:click="toggleFavoriteProduct({{ $product->id }})" class="text-lg {{ $product->is_favorite ? 'text-yellow-400' : 'text-gray-600' }}">
                                                    {{ $product->is_favorite ? '★' : '☆' }}
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-6 pt-6 border-t border-gray-800">
                        <button type="button" wire:click="closeCreate" class="font-black text-gray-500 uppercase tracking-widest text-xs hover:text-white transition-colors">Cancelar</button>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white font-black uppercase tracking-widest text-xs px-12 py-4 rounded-2xl shadow-xl shadow-indigo-500/20">
                            Guardar Plato
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