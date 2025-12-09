<div>
    {{-- Header con botón --}}
    <button wire:click="openCreate"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow">
        Añadir Producto
    </button>

    {{-- Overlay / sección de creación (aparece encima) --}}
    @if($showCreate)
        <div class="fixed inset-0 z-40 flex items-start justify-center pt-20">
            <div class="absolute inset-0 bg-black opacity-40" wire:click="closeCreate"></div>

            <div class="bg-white rounded-lg shadow-xl z-50 w-11/12 md:w-3/4 lg:w-1/2 p-6">
                <div class="flex justify-between items-center mb-4">
                    <h4 class="font-semibold text-lg">Añadir Producto</h4>
                    <button wire:click="closeCreate" class="text-gray-600 hover:text-gray-800">Cerrar ✕</button>
                </div>

                @if (session()->has('error'))
                    <div class="mb-2 text-red-600">{{ session('error') }}</div>
                @endif
                @if (session()->has('success'))
                    <div class="mb-2 text-green-600">{{ session('success') }}</div>
                @endif

                <form wire:submit.prevent="createProduct" class="space-y-3">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm">Nombre *</label>
                            <input wire:model.defer="name" type="text" class="border rounded w-full p-2" required>
                            @error('name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm">Categoría</label>
                            <select wire:model.defer="category_id" class="border rounded w-full p-2">
                                <option value="">-- Ninguna --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm">Calorías</label>
                            <input wire:model.defer="calories" type="number" step="any" class="border rounded w-full p-2">
                            @error('calories') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm">Total fat</label>
                            <input wire:model.defer="total_fat" type="number" step="any" class="border rounded w-full p-2">
                        </div>

                        <div>
                            <label class="block text-sm">Saturated fat</label>
                            <input wire:model.defer="saturated_fat" type="number" step="any" class="border rounded w-full p-2">
                        </div>

                        <div>
                            <label class="block text-sm">Trans fat</label>
                            <input wire:model.defer="trans_fat" type="number" step="any" class="border rounded w-full p-2">
                        </div>

                        <div>
                            <label class="block text-sm">Colesterol</label>
                            <input wire:model.defer="colesterol" type="number" step="any" class="border rounded w-full p-2">
                        </div>

                        <div>
                            <label class="block text-sm">Carbohydrates</label>
                            <input wire:model.defer="carbohydrates" type="number" step="any" class="border rounded w-full p-2">
                        </div>

                        <div>
                            <label class="block text-sm">Fiber</label>
                            <input wire:model.defer="fiber" type="number" step="any" class="border rounded w-full p-2">
                        </div>

                        <div>
                            <label class="block text-sm">Proteins</label>
                            <input wire:model.defer="proteins" type="number" step="any" class="border rounded w-full p-2">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm">External ID</label>
                            <input wire:model.defer="external_id" type="text" class="border rounded w-full p-2">
                        </div>
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