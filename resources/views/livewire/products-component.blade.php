<div>

    {{-- Botón de añadir producto --}}
    <button 
        wire:click="openModal"
        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow mb-4">
        Añadir Producto
    </button>

    

    {{-- Modal de creación --}}
    @if($modal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white p-6 rounded shadow-lg w-2/3 max-h-[90vh] overflow-y-auto">

                <h2 class="text-xl font-bold mb-4">Añadir Producto</h2>

                {{-- FORMULARIO --}}
                <div class="grid grid-cols-2 gap-4">

                    <div>
                        <label>Nombre</label>
                        <input wire:model="name" class="border p-2 rounded w-full">
                    </div>

                    <div>
                        <label>Calorías</label>
                        <input wire:model="calories" type="number" class="border p-2 rounded w-full">
                    </div>

                    <div>
                        <label>Total Fat</label>
                        <input wire:model="total_fat" type="number" class="border p-2 rounded w-full">
                    </div>

                    <div>
                        <label>Saturated Fat</label>
                        <input wire:model="saturated_fat" type="number" class="border p-2 rounded w-full">
                    </div>

                    <div>
                        <label>Trans Fat</label>
                        <input wire:model="trans_fat" type="number" class="border p-2 rounded w-full">
                    </div>

                    <div>
                        <label>Colesterol</label>
                        <input wire:model="colesterol" type="number" class="border p-2 rounded w-full">
                    </div>

                    <div>
                        <label>Polyunsaturated Fat</label>
                        <input wire:model="polyunsaturated_fat" type="number" class="border p-2 rounded w-full">
                    </div>

                    <div>
                        <label>Monounsaturated Fat</label>
                        <input wire:model="monounsaturated_fat" type="number" class="border p-2 rounded w-full">
                    </div>

                    <div>
                        <label>Carbohydrates</label>
                        <input wire:model="carbohydrates" type="number" class="border p-2 rounded w-full">
                    </div>

                    <div>
                        <label>Fiber</label>
                        <input wire:model="fiber" type="number" class="border p-2 rounded w-full">
                    </div>

                    <div>
                        <label>Proteins</label>
                        <input wire:model="proteins" type="number" class="border p-2 rounded w-full">
                    </div>

                    <div>
                        <label>Categoría</label>
                        <input wire:model="category_id" type="number" class="border p-2 rounded w-full">
                    </div>

                    <div class="col-span-2">
                        <label>External ID</label>
                        <input wire:model="external_id" class="border p-2 rounded w-full">
                    </div>

                </div>

                {{-- Botones --}}
                <div class="mt-4 flex justify-end gap-2">
                    <button wire:click="closeModal" class="px-4 py-2 bg-gray-300 rounded">
                        Cancelar
                    </button>

                    <button wire:click="createProduct" class="px-4 py-2 bg-green-600 text-white rounded">
                        Guardar
                    </button>
                </div>

            </div>
        </div>
    @endif

</div>
