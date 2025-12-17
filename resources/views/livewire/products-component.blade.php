<div class="flex flex-col lg:flex-row gap-6">
    <!-- Botón Crear Producto -->
    <div class="lg:w-1/3 bg-gray-800 rounded-lg border border-gray-700 p-4 flex flex-col justify-between">
        <div class="mb-4 text-gray-300">
            <h2 class="text-yellow-400 text-3xl">Productos</h2>
            <p>Añade nuevos productos personales para crear tus platos personales</p>
        </div>
        <button wire:click="openCreate" class="bg-yellow-400 hover:bg-yellow-500 text-white px-4 py-2 rounded shadow mb-4 w-full lg:w-auto">
            Añadir Producto
        </button>
        

        @if($showCreate)
            <div class="fixed inset-0 z-40 flex items-start justify-center pt-20">
                <div class="absolute inset-0 bg-black/70" wire:click="closeCreate"></div>

                <div class="bg-gray-900 text-gray-100 rounded-xl shadow-xl z-50 w-11/12 md:w-3/4 lg:w-1/2 p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="font-semibold text-lg text-yellow-400">Añadir Producto</h4>
                        <button wire:click="closeCreate" class="text-gray-400 hover:text-gray-200">Cerrar ✕</button>
                    </div>

                    @if(session()->has('error'))
                        <div class="mb-2 text-red-600">{{ session('error') }}</div>
                    @endif
                    @if(session()->has('success'))
                        <div class="mb-2 text-green-400">{{ session('success') }}</div>
                    @endif

                    <form wire:submit.prevent="createProduct" class="space-y-3">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm">Nombre *</label>
                                <input wire:model.defer="name" type="text"
                                    class="border border-gray-700 rounded w-full p-2 bg-gray-800 text-gray-100" required>
                                @error('name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm">Categoría</label>
                                <select wire:model.defer="category_id" class="border border-gray-700 rounded w-full p-2 bg-gray-800 text-gray-100">
                                    <option value="">-- Ninguna --</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                            </div>

                            @foreach(['calories','total_fat','saturated_fat','trans_fat','colesterol','carbohydrates','fiber','proteins'] as $field)
                            <div>
                                <label class="block text-sm">{{ ucfirst(str_replace('_',' ',$field)) }}</label>
                                <input wire:model.defer="{{ $field }}" type="number" step="any"
                                    class="border border-gray-700 rounded w-full p-2 bg-gray-800 text-gray-100">
                            </div>
                            @endforeach

                            <div class="md:col-span-2">
                                <label class="block text-sm">External ID</label>
                                <input wire:model.defer="external_id" type="text"
                                    class="border border-gray-700 rounded w-full p-2 bg-gray-800 text-gray-100">
                            </div>
                        </div>

                        <div class="flex justify-end space-x-2 mt-4">
                            <button type="button" wire:click="closeCreate"
                                    class="px-4 py-2 border border-gray-700 rounded text-white hover:text-white">Cancelar</button>
                            <button type="submit" class="px-4 py-2 rounded bg-green-600 hover:bg-green-700 text-white">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>

    <!-- Lista de Productos del Usuario -->
    <div class="flex-1 bg-gray-800 rounded-lg border border-gray-700 p-4 overflow-y-auto max-h-96">
        <h2 class="text-yellow-400 text-3xl">Productos personales</h2>
        @if($userProducts->count() === 0)
            <p class="text-gray-400">No tienes productos creados.</p>
        @else
            <ul class="divide-y divide-gray-700">
                @foreach($userProducts as $product)
                    <li class="flex justify-between items-center px-4 py-2 hover:bg-gray-700">
                        <div>
                            <p class="font-medium text-gray-200">{{ $product->name }}</p>
                            <p class="text-gray-400 text-sm">
                                {{ $product->category->name ?? 'Sin categoría' }} |
                                {{ $product->calories ?? 0 }} kcal |
                                Proteínas: {{ $product->proteins ?? 0 }} g
                            </p>
                        </div>
                        <button wire:click="deleteProduct({{ $product->id }})" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded shadow text-sm">
                            Eliminar
                        </button>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>