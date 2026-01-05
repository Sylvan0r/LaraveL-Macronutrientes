<div class="bg-gray-800 p-6 rounded-lg border border-gray-700 space-y-6">

    <h2 class="text-yellow-400 text-3xl">Objetivos nutricionales</h2>

    @if(session()->has('success'))
        <div class="text-green-400">{{ session('success') }}</div>
    @endif

    <!-- Objetivos -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="text-sm">Calorías</label>
            <input type="number" wire:model.defer="calories" class="w-full p-2 bg-gray-700 rounded">
        </div>
        <div>
            <label class="text-sm">Proteínas (g)</label>
            <input type="number" wire:model.defer="proteins" class="w-full p-2 bg-gray-700 rounded">
        </div>
        <div>
            <label class="text-sm">Grasas (g)</label>
            <input type="number" wire:model.defer="fats" class="w-full p-2 bg-gray-700 rounded">
        </div>
        <div>
            <label class="text-sm">Carbohidratos (g)</label>
            <input type="number" wire:model.defer="carbohydrates" class="w-full p-2 bg-gray-700 rounded">
        </div>
    </div>

    <button wire:click="saveGoals" class="bg-yellow-400 text-black px-4 py-2 rounded">
        Guardar objetivos
    </button>

    <!-- Progreso diario -->
    <div class="space-y-3">
        @foreach(['calories','proteins','fats','carbohydrates'] as $nutrient)
            @php $percent = $this->percentage($nutrient); @endphp

            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span class="capitalize">{{ $nutrient }}</span>
                    <span>{{ $consumed[$nutrient] ?? 0 }} / {{ $this->$nutrient ?? 0 }}</span>
                </div>

                <div class="w-full bg-gray-700 rounded h-3">
                    <div
                        class="h-3 rounded
                        {{ $percent < 90 ? 'bg-yellow-400' : ($percent <= 100 ? 'bg-green-500' : 'bg-red-500') }}"
                        style="width: {{ min($percent,100) }}%">
                    </div>
                </div>

                <p class="text-xs mt-1">
                    @if($percent > 100)
                        🔴 Objetivo superado
                    @elseif($percent >= 90)
                        🟢 Objetivo casi cumplido
                    @else
                        🟡 Aún no alcanzado
                    @endif
                    ({{ $percent }}%)
                </p>
            </div>
        @endforeach
    </div>
</div>
