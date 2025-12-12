<div>
    <button wire:click="openCreate" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow mb-4">
        Crear Menú
    </button>

    @if($showCreate)
        <div class="fixed inset-0 z-40 flex items-start justify-center pt-20">
            <div class="absolute inset-0 bg-black opacity-40" wire:click="closeCreate"></div>

            <div class="bg-white rounded-lg shadow-xl z-50 w-11/12 md:w-3/4 lg:w-1/2 p-6">
                <div class="flex justify-between items-center mb-4">
                    <h4 class="font-semibold text-lg">Crear Menú</h4>
                    <button wire:click="closeCreate" class="text-gray-600 hover:text-gray-800">Cerrar ✕</button>
                </div>

                @if(session()->has('success'))
                    <div class="mb-2 text-green-600">{{ session('success') }}</div>
                @endif

                <form wire:submit.prevent="createMenu" class="space-y-4">

                    {{-- Nombre del menú --}}
                    <div>
                        <label class="block text-sm">Nombre *</label>
                        <input wire:model.defer="name" type="text" class="border rounded w-full p-2" required>
                        @error('name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    {{-- Selección de platos y cantidad --}}
                    <div>
                        <label class="block text-sm mb-1">Selecciona Platos y Cantidad *</label>
                        <div class="space-y-2 max-h-64 overflow-y-auto border rounded p-2">
                            @foreach($userPlatos as $plato)
                                <div class="flex items-center justify-between gap-2">
                                    <label class="flex-1">
                                        <input type="checkbox" wire:model="selectedPlatos" value="{{ $plato->id }}">
                                        {{ $plato->name }} - {{ $plato->descripcion ?? '' }}
                                    </label>
                                    <input type="number" wire:model.defer="quantities.{{ $plato->id }}" min="1" class="w-20 border rounded p-1" value="1">
                                </div>
                            @endforeach
                        </div>
                        @error('selectedPlatos') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        @error('quantities') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end space-x-2 mt-4">
                        <button type="button" wire:click="closeCreate" class="px-4 py-2 rounded border">Cancelar</button>
                        <button type="submit" class="px-4 py-2 rounded bg-green-600 text-white">Guardar</button>
                    </div>

                </form>
            </div>
        </div>
    @endif
</div>