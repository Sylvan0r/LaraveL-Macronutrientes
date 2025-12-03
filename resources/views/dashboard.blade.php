<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="font-bold text-lg mb-4">Selecciona un producto:</h3>

                <select id="product-select" class="border p-2 rounded">
                    <option value="">-- Selecciona un producto --</option>
                    @foreach (\App\Models\Product::all() as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>

                <div id="product-info" class="mt-6"></div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            const select = document.getElementById('product-select');
            const infoDiv = document.getElementById('product-info');

            select.addEventListener('change', function() {
                const productId = this.value;

                if (!productId) {
                    infoDiv.innerHTML = '';
                    return;
                }

                fetch(`/product/${productId}`)
                    .then(res => res.json())
                    .then(data => {
                        infoDiv.innerHTML = `
                            <h4 class="font-bold text-lg mb-2">${data.name}</h4>
                            <ul class="list-disc pl-6">
                                <li>Calorías: ${data.calories ?? 'N/A'}</li>
                                <li>Grasa total: ${data.total_fat ?? 'N/A'} g</li>
                                <li>Grasa saturada: ${data.saturated_fat ?? 'N/A'} g</li>
                                <li>Grasa trans: ${data.trans_fat ?? 'N/A'} g</li>
                                <li>Poliinsaturada: ${data.polyunsaturated_fat ?? 'N/A'} g</li>
                                <li>Monoinsaturada: ${data.monounsaturated_fat ?? 'N/A'} g</li>
                                <li>Carbohidratos: ${data.carbohydrates ?? 'N/A'} g</li>
                                <li>Fibra: ${data.fiber ?? 'N/A'} g</li>
                                <li>Proteínas: ${data.proteins ?? 'N/A'} g</li>
                            </ul>
                        `;
                    })
                    .catch(err => {
                        infoDiv.innerHTML = `<p class="text-red-500">Error al cargar el producto</p>`;
                        console.error(err);
                    });
            });
        </script>
    @endpush
</x-app-layout>
