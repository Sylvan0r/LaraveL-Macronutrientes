<div class="flex flex-col lg:flex-row gap-6">

    <!-- ======================
         PANEL CREAR MENÚ
    ======================= -->
    <div class="lg:w-1/3 bg-gray-800 rounded-lg border border-gray-700 p-4 flex flex-col justify-between">
        <div class="mb-4 text-gray-300">
            <h2 class="text-yellow-400 text-3xl">Menús</h2>
            <p>Crea menús usando tus platos creados.</p>
        </div>

        <button
            wire:click="openCreate"
            class="bg-yellow-400 hover:bg-yellow-500 text-black px-4 py-2 rounded shadow w-full"
        >
            Crear Menú
        </button>

        @if($showCreate)
            <div class="fixed inset-0 z-40 flex items-start justify-center pt-20">
                <div class="absolute inset-0 bg-black/70" wire:click="closeCreate"></div>

                <div class="bg-gray-900 text-gray-100 rounded-xl shadow-xl z-50 w-11/12 md:w-3/4 lg:w-1/2 p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="text-lg text-yellow-400 font-semibold">Crear Menú</h4>
                        <button wire:click="closeCreate" class="text-gray-400 hover:text-gray-200">✕</button>
                    </div>

                    <form wire:submit.prevent="createMenu" class="space-y-4">
                        <div>
                            <label class="block text-sm mb-1">Nombre *</label>
                            <input
                                wire:model.defer="name"
                                type="text"
                                class="w-full bg-gray-800 border border-gray-700 rounded p-2 text-white"
                                required
                            >
                        </div>

                        <div>
                            <label class="block text-sm mb-1">Platos *</label>

                            <div class="max-h-64 overflow-y-auto border border-gray-700 rounded p-2 space-y-2">
                                @foreach($userPlatos as $plato)
                                    <div class="flex items-center justify-between gap-2">
                                        <label class="flex items-center gap-2 flex-1">
                                            <input
                                                type="checkbox"
                                                wire:model="selectedPlatos"
                                                value="{{ $plato->id }}"
                                            >

                                            <span>{{ $plato->name }}</span>
                                        </label>

                                        <input
                                            type="number"
                                            min="1"
                                            wire:model.defer="quantities.{{ $plato->id }}"
                                            class="w-16 bg-gray-800 border border-gray-700 rounded p-1 text-white"
                                            placeholder="1"
                                        >

                                        <!-- ⭐ FAVORITO -->
                                        <button
                                            type="button"
                                            wire:click.stop="togglePlatoFavorite({{ $plato->id }})"
                                            class="{{ $plato->is_favorite ? 'text-yellow-400' : 'text-gray-500' }} text-lg"
                                            title="Marcar como favorito"
                                        >
                                            ★
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="flex justify-end gap-2">
                            <button
                                type="button"
                                wire:click="closeCreate"
                                class="px-4 py-2 border border-gray-700 rounded text-gray-300 hover:text-white"
                            >
                                Cancelar
                            </button>

                            <button
                                type="submit"
                                class="px-4 py-2 bg-green-600 hover:bg-green-700 rounded text-white"
                            >
                                Guardar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>

    <!-- ======================
         LISTADO DE MENÚS
    ======================= -->
    <div class="lg:w-2/3 bg-gray-800 rounded-lg border border-gray-700 p-4 max-h-96 overflow-y-auto">
        <h2 class="text-yellow-400 text-3xl mb-2">Menús personales</h2>

        @if($userMenus->isEmpty())
            <p class="text-gray-400">No tienes menús creados.</p>
        @else
            <ul class="divide-y divide-gray-700">
                @foreach($userMenus as $menu)
                    <li class="py-3 px-2 hover:bg-gray-700 rounded">
                        <div class="flex justify-between gap-4">

                            <div class="flex-1">
                                <p class="font-semibold text-gray-200">{{ $menu->name }}</p>

                                @foreach($menu->platos as $plato)
                                    <div class="flex items-center gap-2 text-sm text-gray-400 mt-1">

                                        <!-- ⭐ FAVORITO -->
                                        <button
                                            wire:click="togglePlatoFavorite({{ $plato->id }})"
                                            class="{{ $plato->is_favorite ? 'text-yellow-400' : 'text-gray-500' }} text-lg"
                                            title="Marcar como favorito"
                                        >
                                            ★
                                        </button>

                                        <span>
                                            {{ $plato->name }} (x{{ $plato->pivot->quantity }})
                                        </span>
                                    </div>
                                @endforeach
                            </div>

                            <button
                                wire:click="deleteMenu({{ $menu->id }})"
                                class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded h-fit"
                            >
                                Eliminar
                            </button>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>