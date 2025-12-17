<div class="flex flex-col lg:flex-row gap-6">
    <!-- Botón + Texto (30%) -->
    <div class="lg:w-1/3 bg-gray-800 rounded-lg border border-gray-700 p-4 flex flex-col justify-between">
        <div class="mb-4 text-gray-300">
            <h2 class="text-yellow-400 text-3xl">Menus</h2>
            <p>Crea menús usando tus platos creados.</p>
        </div>
        <button wire:click="openCreate" class="bg-yellow-400 hover:bg-yellow-500 text-white px-4 py-2 rounded shadow w-full">
            Crear Menú
        </button>

        @if($showCreate)
            <div class="fixed inset-0 z-40 flex items-start justify-center pt-20">
                <div class="absolute inset-0 bg-black/70" wire:click="closeCreate"></div>

                <div class="bg-gray-900 text-gray-100 rounded-xl shadow-xl z-50 w-11/12 md:w-3/4 lg:w-1/2 p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="font-semibold text-lg text-yellow-400">Crear Menú</h4>
                        <button wire:click="closeCreate" class="text-gray-400 hover:text-gray-200">Cerrar ✕</button>
                    </div>

                    @if(session()->has('success'))
                        <div class="mb-2 text-green-400">{{ session('success') }}</div>
                    @endif

                    <form wire:submit.prevent="createMenu" class="space-y-4">
                        <div>
                            <label class="block text-sm mb-1">Nombre *</label>
                            <input wire:model.defer="name" type="text"
                                   class="w-full bg-gray-800 border border-gray-700 rounded p-2 text-gray-100"
                                   required>
                        </div>

                        <div>
                            <label class="block text-sm mb-1">Selecciona Platos y Cantidad *</label>
                            <div class="space-y-2 max-h-64 overflow-y-auto border border-gray-700 rounded p-2">
                                @foreach($userPlatos as $plato)
                                    <div class="flex items-center justify-between gap-2">
                                        <label class="flex-1">
                                            <input type="checkbox" wire:model="selectedPlatos" value="{{ $plato->id }}">
                                            {{ $plato->name }} - {{ $plato->descripcion ?? '' }}
                                        </label>
                                        <input type="number" wire:model.defer="quantities.{{ $plato->id }}" min="1"
                                               class="w-20 bg-gray-800 border border-gray-700 rounded p-1 text-gray-100"
                                               value="1">
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="flex justify-end space-x-2 mt-4">
                            <button type="button" wire:click="closeCreate" class="px-4 py-2 rounded border border-gray-700 text-white hover:text-white">Cancelar</button>
                            <button type="submit" class="px-4 py-2 rounded bg-green-600 hover:bg-green-700 text-white">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>

    <!-- Lista de Menús del Usuario (70%) -->
    <div class="lg:w-2/3 bg-gray-800 rounded-lg border border-gray-700 p-4 overflow-y-auto max-h-96">
        <h2 class="text-yellow-400 text-3xl">Menus personales</h2>
        @if($userMenus->count() === 0)
            <p class="text-gray-400">No tienes menús creados.</p>
        @else
            <ul class="divide-y divide-gray-700">
                @foreach($userMenus as $menu)
                    <li class="flex justify-between items-start md:items-center px-4 py-2 hover:bg-gray-700">
                        <div class="flex-1">
                            <p class="font-semibold text-gray-200">{{ $menu->name }}</p>
                            <p class="text-gray-400 text-sm mb-1">
                                @foreach($menu->platos as $plato)
                                    • {{ $plato->name }} (x{{ $plato->pivot->quantity }})
                                    @if(!$loop->last)<br>@endif
                                @endforeach
                            </p>
                        </div>
                        <button wire:click="deleteMenu({{ $menu->id }})" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded shadow text-sm mt-2 md:mt-0">Eliminar</button>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>