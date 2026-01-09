<div class="space-y-8 p-4">
    {{-- Formulario de Ajuste de Objetivos --}}
    <div class="bg-gray-900/50 p-8 rounded-[3rem] border border-gray-700/50 backdrop-blur-md shadow-2xl">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h4 class="text-sm font-black uppercase tracking-[0.3em] text-emerald-400">Configurar Metas Diarias</h4>
                <p class="text-[10px] text-gray-500 font-bold uppercase mt-1">Define tus límites nutricionales personalizados</p>
            </div>
            @if(session()->has('success'))
                <span x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                      class="text-[10px] bg-emerald-500/20 text-emerald-400 px-4 py-2 rounded-xl font-black border border-emerald-500/30">
                    ✓ CAMBIOS GUARDADOS
                </span>
            @endif
        </div>

        <div class="grid grid-cols-2 md:grid-cols-5 gap-6">
            @php
                // Nombres ajustados para coincidir con el componente y la DB
                $fields = [
                    'calories' => ['Calorías', 'kcal'],
                    'proteins' => ['Proteínas', 'g'],
                    'carbohydrates' => ['Carbs', 'g'],
                    'fats' => ['Grasas Tot.', 'g'],
                    'saturated_fat' => ['Saturadas', 'g'],
                    'trans_fat' => ['Trans', 'g'],
                    'polyunsaturated_fat' => ['Poliinsat.', 'g'],
                    'monounsaturated_fat' => ['Monoinsat.', 'g'],
                    'fiber' => ['Fibra', 'g'],
                    'colesterol' => ['Colesterol', 'mg']
                ];
            @endphp

            @foreach($fields as $key => $info)
                <div class="relative group">
                    <label class="text-[9px] font-black text-gray-500 uppercase ml-2 mb-1 block group-hover:text-yellow-400 transition-colors">
                        {{ $info[0] }}
                    </label>
                    <div class="relative">
                        <input type="number" 
                               wire:model.defer="{{ $key }}" 
                               step="any"
                               class="w-full bg-gray-800 border-gray-700 rounded-2xl p-4 text-white focus:ring-2 focus:ring-yellow-400 font-black transition-all text-lg shadow-inner @error($key) border-rose-500 @enderror">
                        <span class="absolute right-3 bottom-2 text-[8px] font-black text-gray-600 uppercase">{{ $info[1] }}</span>
                    </div>
                </div>
            @endforeach
        </div>

        <button wire:click="saveGoals" 
                wire:loading.attr="disabled"
                class="w-full mt-8 bg-yellow-400 hover:bg-yellow-300 disabled:opacity-50 text-gray-900 font-black py-5 rounded-[1.5rem] transition-all shadow-xl shadow-yellow-400/10 active:scale-[0.98] uppercase text-xs tracking-[0.2em]">
            <span wire:loading.remove wire:target="saveGoals">Actualizar Mis Objetivos Nutricionales</span>
            <span wire:loading wire:target="saveGoals">Procesando cambios...</span>
        </button>
    </div>

    {{-- Visualización de Progreso Diario --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($fields as $key => $info)
            @php 
                $percent = $this->percentage($key); 
                // Lógica de colores premium
                $color = $percent > 100 ? 'from-rose-600 to-red-700' : ($percent >= 90 ? 'from-emerald-400 to-emerald-600' : ($percent >= 50 ? 'from-yellow-400 to-orange-500' : 'from-gray-700 to-gray-600'));
                $status = $percent > 100 ? 'Límite superado' : ($percent >= 90 ? 'Objetivo optimizado' : ($percent >= 50 ? 'Progreso adecuado' : 'Pendiente'));
            @endphp

            <div class="bg-gray-800/40 p-6 rounded-[2.5rem] border border-gray-700/50 group hover:bg-gray-800/60 transition-all">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <span class="block text-[10px] font-black uppercase tracking-[0.2em] text-emerald-400 mb-1">{{ $info[0] }}</span>
                        <div class="flex items-center gap-3">
                            <span class="text-4xl font-black text-white italic tracking-tighter">{{ $percent }}%</span>
                        </div>
                    </div>
                    
                    <div class="bg-black/40 px-4 py-3 rounded-2xl border border-gray-700 text-center min-w-[100px]">
                        <span class="block text-[8px] font-black text-gray-500 uppercase mb-1">Tu Meta</span>
                        <span class="text-lg font-black text-yellow-400 leading-none">
                            {{ number_format($this->$key ?? 0, 0) }}<span class="text-[10px] text-gray-600 ml-0.5">{{ $info[1] }}</span>
                        </span>
                    </div>
                </div>

                <div class="space-y-3">
                    <div class="flex justify-between text-[10px] font-black uppercase tracking-widest px-1">
                        <span class="text-gray-400">Consumido: {{ number_format($consumed[$key] ?? 0, 1) }} {{ $info[1] }}</span>
                        <span class="{{ $percent > 100 ? 'text-rose-500' : 'text-gray-500' }}">{{ $status }}</span>
                    </div>
                    
                    <div class="relative h-6 w-full bg-black/60 rounded-full p-1 border border-gray-800 shadow-inner overflow-hidden">
                        <div class="h-full rounded-full bg-gradient-to-r transition-all duration-1000 ease-out {{ $color }} shadow-lg shadow-black/50" 
                             style="width: {{ min($percent, 100) }}%">
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>