<div class="space-y-8">
    {{-- Formulario de Ajuste de Objetivos --}}
    <div class="bg-gray-900/50 p-6 rounded-[2rem] border border-gray-700/50">
        <div class="flex items-center justify-between mb-6">
            <h4 class="text-sm font-black uppercase tracking-[0.2em] text-gray-400">Configurar Metas</h4>
            @if(session()->has('success'))
                <span class="text-[10px] bg-emerald-500/20 text-emerald-400 px-3 py-1 rounded-full font-bold animate-pulse">
                    ✓ Guardado
                </span>
            @endif
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach(['calories' => 'Calorías', 'proteins' => 'Proteínas (g)', 'fats' => 'Grasas (g)', 'carbohydrates' => 'Carbs (g)'] as $key => $label)
                <div class="space-y-1">
                    <label class="text-[10px] font-black text-gray-500 uppercase ml-2">{{ $label }}</label>
                    <input type="number" wire:model.defer="{{ $key }}" 
                           class="w-full bg-gray-800 border-gray-700 rounded-xl p-3 text-white focus:ring-2 focus:ring-yellow-400 font-bold transition-all">
                </div>
            @endforeach
        </div>

        <button wire:click="saveGoals" class="w-full mt-6 bg-yellow-400 hover:bg-yellow-300 text-gray-900 font-black py-3 rounded-xl transition-all shadow-lg shadow-yellow-400/10 active:scale-[0.98] uppercase text-xs tracking-widest">
            Actualizar Mis Objetivos
        </button>
    </div>

    {{-- Visualización de Progreso --}}
    <div class="grid grid-cols-1 gap-6">
        @foreach(['calories','proteins','fats','carbohydrates'] as $nutrient)
            @php 
                $percent = $this->percentage($nutrient); 
                // Lógica de colores basada en el diseño premium
                if ($percent > 100) { $color = 'from-orange-600 to-rose-600'; $shadow = 'shadow-rose-500/20'; $status = 'Límite superado'; }
                elseif ($percent >= 90) { $color = 'from-emerald-400 to-emerald-600'; $shadow = 'shadow-emerald-500/20'; $status = 'Objetivo alcanzado'; }
                elseif ($percent >= 50) { $color = 'from-yellow-400 to-yellow-500'; $shadow = 'shadow-yellow-400/20'; $status = 'En buen camino'; }
                else { $color = 'from-gray-600 to-gray-500'; $shadow = 'shadow-white/5'; $status = 'Pendiente'; }
            @endphp

            <div class="bg-gray-800/20 p-5 rounded-2xl border border-gray-700/30">
                <div class="flex justify-between items-end mb-3">
                    <div>
                        <span class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">{{ $nutrient == 'fats' ? 'Grasas' : ($nutrient == 'calories' ? 'Energía' : ($nutrient == 'proteins' ? 'Proteína' : 'Carbohidratos')) }}</span>
                        <span class="text-xl font-black text-white italic uppercase tracking-tighter">{{ $percent }}%</span>
                    </div>
                    <div class="text-right">
                        <span class="text-xs font-bold text-gray-400">{{ $consumed[$nutrient] ?? 0 }}</span>
                        <span class="text-[10px] font-black text-gray-600 uppercase">/ {{ $this->$nutrient ?? 0 }}</span>
                    </div>
                </div>

                <div class="relative h-4 w-full bg-gray-900 rounded-full overflow-hidden border border-gray-800 shadow-inner">
                    <div class="absolute top-0 left-0 h-full rounded-full bg-gradient-to-r transition-all duration-1000 ease-out {{ $color }} {{ $shadow }}" 
                         style="width: {{ min($percent, 100) }}%">
                        <div class="w-full h-full opacity-30 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')]"></div>
                    </div>
                </div>
                <p class="text-[9px] font-bold mt-2 uppercase tracking-widest {{ $percent > 100 ? 'text-rose-400' : 'text-gray-500' }}">
                    ● {{ $status }}
                </p>
            </div>
        @endforeach
    </div>
</div>