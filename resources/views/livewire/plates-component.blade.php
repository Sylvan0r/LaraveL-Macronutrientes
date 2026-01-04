<div class="flex flex-col lg:flex-row gap-6">

    {{-- PANEL CREAR --}}
    <div class="lg:w-1/3 bg-gray-800 rounded-lg border border-gray-700 p-4">
        <h2 class="text-yellow-400 text-3xl mb-2">Platos</h2>
        <p class="text-gray-400 mb-4">Crea platos usando productos públicos o propios.</p>

        <button wire:click="openCreate"
            class="bg-yellow-400 hover:bg-yellow-500 text-black px-4 py-2 rounded w-full">
            Crear Plato
        </button>

        {{-- MODAL --}}
        @if($showCreate)
            <div class="fixed inset-0 z-40 flex justify-center pt-20">
                <div class="absolute inset-0 bg-black/70" wire:click="closeCreate"></div>

                <div class="bg-gray-900 text-gray-100 rounded-xl shadow-xl z-50 w-11/12 md:w-3/4 lg:w-1/2 p-6">

                    <div class="flex justify-between mb-4">
                        <h4 class="text-yellow-400 text-lg">Crear Plato</h4>
                        <button wire:click="closeCreate">✕</button>
                    </div>

                    <form wire:submit.prevent="createPlato" class="space-y-4">

                        <input wire:model.defer="name"
                            placeholder="Nombre"
                            class="w-full bg-gray-800 border border-gray-700 p-2 rounded">

                        <textarea wire:model.defer="descripcion"
                            placeholder="Descripción"
                            class="w-full bg-gray-800 border border-gray-700 p-2 rounded"></textarea>

                        <div class="grid grid-cols-2 gap-4">

                            {{-- PRODUCTOS PÚBLICOS --}}
                            <div>
                                <h5 class="text-yellow-400 mb-1">Públicos</h5>
                                <div class="max-h-48 overflow-y-auto border border-gray-700 rounded p-2">
                                    @foreach($publicProducts as $product)
                                        <div class="flex justify-between mb-1">
                                            <label>
                                                <input type="checkbox" wire:model="selectedProducts" value="{{ $product->id }}">
                                                {{ $product->name }}
                                            </label>
                                            <button type="button"
                                                wire:click="toggleFavoriteProduct({{ $product->id }})"
                                                class="text-yellow-400">
                                                {{ $product->is_favorite ? '★' : '☆' }}
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- PRODUCTOS USUARIO --}}
                            <div>
                                <h5 class="text-yellow-400 mb-1">Tus productos</h5>
                                <div class="max-h-48 overflow-y-auto border border-gray-700 rounded p-2">
                                    @foreach($userProducts as $product)
                                        <div class="flex justify-between mb-1">
                                            <label>
                                                <input type="checkbox" wire:model="selectedProducts" value="{{ $product->id }}">
                                                {{ $product->name }}
                                            </label>
                                            <button type="button"
                                                wire:click="toggleFavoriteProduct({{ $product->id }})"
                                                class="text-yellow-400">
                                                {{ $product->is_favorite ? '★' : '☆' }}
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                        </div>

                        <div class="flex justify-end gap-2">
                            <button type="button" wire:click="closeCreate"
                                class="border border-gray-700 px-4 py-2 rounded">
                                Cancelar
                            </button>
                            <button class="bg-green-600 px-4 py-2 rounded">
                                Guardar
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        @endif
    </div>

    {{-- LISTA PLATOS --}}
    <div class="lg:w-2/3 bg-gray-800 rounded-lg border border-gray-700 p-4 max-h-96 overflow-y-auto">
        <h2 class="text-yellow-400 text-3xl mb-4">Platos personales</h2>

        <ul class="divide-y divide-gray-700">
            @foreach($userPlates as $plato)
                <li class="flex justify-between py-2">
                    <div>
                        <p class="text-gray-200 font-semibold">
                            {{ $plato->is_favorite ? '★' : '' }} {{ $plato->name }}
                        </p>
                        <p class="text-gray-400 text-sm">{{ $plato->descripcion }}</p>
                    </div>

                    <div class="flex gap-2">
                        <button wire:click="togglePlateFavorite({{ $plato->id }})"
                            class="text-yellow-400 text-xl">
                            {{ $plato->is_favorite ? '★' : '☆' }}
                        </button>

                        <button wire:click="deletePlate({{ $plato->id }})"
                            class="bg-red-600 px-3 py-1 rounded text-white">
                            Eliminar
                        </button>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</div>