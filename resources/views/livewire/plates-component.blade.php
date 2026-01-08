<div class="flex flex-col lg:flex-row gap-6">
    <!-- Panel Crear Plato -->
    <div class="lg:w-1/3 bg-gray-800 rounded-lg border border-gray-700 p-4 flex flex-col justify-between">
        <div class="mb-4 text-gray-300">
            <h2 class="text-yellow-400 text-3xl">Platos</h2>
            <p>Añade nuevos platos usando productos públicos o tus productos personales.</p>
        </div>
        <button wire:click="openCreate" class="bg-yellow-400 hover:bg-yellow-500 text-black px-4 py-2 rounded shadow w-full">
            Crear Plato
        </button>

        @if($showCreate)
            <div class="fixed inset-0 z-40 flex items-start justify-center pt-20">
                <div class="absolute inset-0 bg-black/70" wire:click="closeCreate"></div>

                <div class="bg-gray-900 text-gray-100 rounded-xl shadow-xl z-50 w-11/12 md:w-3/4 lg:w-1/2 p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="font-semibold text-lg text-yellow-400">Crear Plato</h4>
                        <button wire:click="closeCreate" class="text-gray-400 hover:text-gray-200">✕</button>
                    </div>

                    @if(session()->has('success'))
                        <div class="mb-3 text-green-400 text-sm">{{ session('success') }}</div>
                    @endif

                    <form wire:submit.prevent="createPlato" class="space-y-4">
                        <div>
                            <label class="block text-sm mb-1">Nombre *</label>
                            <input wire:model.defer="name" class="w-full bg-gray-800 border border-gray-700 rounded p-2 text-gray-100" required>
                        </div>

                        <div>
                            <label class="block text-sm mb-1">Descripción</label>
                            <textarea wire:model.defer="descripcion" rows="3" class="w-full bg-gray-800 border border-gray-700 rounded p-2 text-gray-100"></textarea>
                        </div>

                        <div>
                            <label class="block text-sm mb-2">Productos *</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Productos públicos -->
                                <div>
                                    <h5 class="font-semibold mb-1 text-yellow-400">Productos públicos</h5>
                                    <div class="max-h-56 overflow-y-auto border border-gray-700 rounded p-2 space-y-1">
                                        @foreach($publicProducts as $product)
                                            <div wire:key="public-product-{{ $product->id }}" class="flex justify-between gap-2 items-center">
                                                <div class="flex items-center gap-2">
                                                    <input type="checkbox" wire:model="selectedProducts" value="{{ $product->id }}">
                                                    <span>{{ $product->name }}</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <input type="number" min="0" wire:model.defer="quantities.{{ $product->id }}" class="w-16 bg-gray-800 border border-gray-700 rounded p-1 text-gray-100">
                                                    <button type="button" wire:click="toggleFavoriteProduct({{ $product->id }})" class="text-yellow-400">
                                                        {{ $product->is_favorite ? '★' : '☆' }}
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                        @if($publicProducts->count() === 0)
                                            <p class="text-gray-400 text-sm">No hay productos públicos disponibles.</p>
                                        @endif
                                    </div>
                                </div>

                                <!-- Productos del usuario -->
                                <div>
                                    <h5 class="font-semibold mb-1 text-yellow-400">Tus productos</h5>
                                    <div class="max-h-56 overflow-y-auto border border-gray-700 rounded p-2 space-y-1">
                                        @foreach($userProducts as $product)
                                            <div wire:key="user-product-{{ $product->id }}" class="flex justify-between gap-2 items-center">
                                                <div class="flex items-center gap-2">
                                                    <input type="checkbox" wire:model="selectedProducts" value="{{ $product->id }}">
                                                    <span>{{ $product->name }}</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <input type="number" min="0" wire:model.defer="quantities.{{ $product->id }}" class="w-16 bg-gray-800 border border-gray-700 rounded p-1 text-gray-100">
                                                    <button type="button" wire:click="toggleFavoriteProduct({{ $product->id }})" class="text-yellow-400">
                                                        {{ $product->is_favorite ? '★' : '☆' }}
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                        @if($userProducts->count() === 0)
                                            <p class="text-gray-400 text-sm">No tienes productos disponibles.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end gap-2">
                            <button type="button" wire:click="closeCreate" class="px-4 py-2 border border-gray-700 rounded text-white hover:text-white">Cancelar</button>
                            <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 rounded text-white">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>

    <!-- Lista de Platos del Usuario -->
    <div class="lg:w-2/3 bg-gray-800 rounded-lg border border-gray-700 p-4 overflow-y-auto max-h-96">
        <h2 class="text-yellow-400 text-3xl">Platos personales</h2>
        @if($userPlates->count() === 0)
            <p class="text-gray-400">No tienes platos creados.</p>
        @else
            <ul class="divide-y divide-gray-700">
                @foreach($userPlates as $plato)
                    <li wire:key="plato-{{ $plato->id }}" class="flex justify-between items-start md:items-center px-4 py-2 hover:bg-gray-700">
                        <div class="flex-1">
                            <p class="font-semibold text-gray-200">{{ $plato->name }}</p>
                            @if($plato->descripcion)
                                <p class="text-gray-400 text-sm">{{ $plato->descripcion }}</p>
                            @endif
                            @if($plato->products->count())
                                <p class="text-gray-400 text-sm">
                                    @foreach($plato->products as $prod)
                                        {{ $prod->name }} ({{ $prod->category->name ?? 'Sin categoría' }}) - {{ $prod->pivot->quantity }}
                                        @if(!$loop->last), @endif
                                    @endforeach
                                </p>
                            @endif
                        </div>
                        <button wire:click="deletePlate({{ $plato->id }})" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded shadow text-sm mt-2 md:mt-0">Eliminar</button>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>