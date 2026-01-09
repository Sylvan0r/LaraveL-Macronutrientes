<div class="flex flex-col h-full space-y-6">
    {{-- Selector de Fecha --}}
    <div class="relative group">
        <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest ml-4 mb-1 block">Fecha de Consulta</label>
        <div class="relative">
            <input type="date" wire:model.live="selectedDate"
                   class="w-full bg-gray-900 border-2 border-gray-800 rounded-2xl p-4 text-white font-black text-sm focus:border-yellow-400 transition-all outline-none cursor-pointer">
        </div>

        <div class="mt-4">
            <a href="{{ route('pdf.user.platos', ['date' => $selectedDate]) }}" 
            target="_blank"
            class="flex items-center justify-center gap-2 w-full bg-gray-900 border-2 border-gray-800 rounded-2xl p-4 text-yellow-400 font-black text-xs hover:border-yellow-400 transition-all uppercase tracking-widest">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Descargar PDF de esta semana
            </a>
        </div>
    </div>

    {{-- Biblioteca de Menús --}}
    <div class="space-y-3">
        <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] flex items-center gap-2 ml-2">
            <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span> Biblioteca
        </h3>
        <div class="max-h-48 overflow-y-auto custom-scrollbar space-y-2 pr-2">
            @foreach($menus as $menu)
                <div class="flex justify-between items-center bg-gray-900/40 hover:bg-gray-800/60 p-3 rounded-xl border border-gray-800 transition-all">
                    <div class="flex flex-col">
                        <span class="text-xs font-bold text-gray-300">{{ $menu->name }}</span>
                        <span class="text-[8px] font-black text-gray-600 uppercase">{{ round($menu->getMacros()['calories'] ?? 0) }} Kcal</span>
                    </div>
                    <button wire:click="addMenuToDate({{ $menu->id }})"
                            class="text-[9px] font-black bg-emerald-600/10 text-emerald-400 border border-emerald-600/30 px-3 py-1.5 rounded-lg hover:bg-emerald-600 hover:text-white transition-all uppercase">
                        AÑADIR
                    </button>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Plan Activo --}}
    <div class="flex-1 space-y-3">
        <h3 class="text-[10px] font-black text-yellow-400 uppercase tracking-[0.2em] ml-2">Plan Activo</h3>
        <div class="space-y-4 overflow-y-auto custom-scrollbar pr-2" style="max-height: 300px;">
            @forelse($calendarMenus as $menuDay)
                <div class="bg-gray-900 border-l-4 border-yellow-400 p-4 rounded-r-2xl shadow-lg relative group transition-all">
                    <button wire:click="removeMenuFromDate({{ $menuDay->id }})"
                            class="absolute top-2 right-2 text-gray-700 hover:text-rose-500 z-10">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"/></svg>
                    </button>
                    <p class="font-black text-white text-sm uppercase truncate pr-8">{{ $menuDay->menu->name }}</p>
                    <div class="mt-2 space-y-1">
                        @foreach($menuDay->menu->platos as $plato)
                            <div class="flex justify-between text-[10px]">
                                <span class="text-gray-400">{{ $plato->name }}</span>
                                <span class="text-indigo-400 font-black">x{{ $plato->pivot->quantity }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="text-center py-10 border-2 border-dashed border-gray-800 rounded-3xl">
                    <p class="text-[10px] font-black text-gray-700 uppercase">Sin menús hoy</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Balance del Día --}}
    @if($goals)
        <div class="bg-yellow-400 rounded-[2rem] p-5 shadow-xl border-b-4 border-yellow-500">
            <h4 class="text-[10px] font-black text-gray-900 uppercase tracking-[0.2em] mb-4">Balance del Día</h4>
            
            <div class="grid grid-cols-2 sm:grid-cols-5 gap-2">
                @php
                    $nutrients_map = [
                        'calories' => ['Kcal', 'text-gray-900', 'calories'],
                        'proteins' => ['Prot', 'text-indigo-900', 'proteins'],
                        'carbohydrates' => ['Carb', 'text-emerald-900', 'carbohydrates'],
                        'fats' => ['Fat', 'text-orange-900', 'fats'],
                        'saturated_fat' => ['Sat', 'text-rose-900', 'saturated_fat'],
                        'trans_fat' => ['Trans', 'text-rose-950', 'trans_fat'],
                        'polyunsaturated_fat' => ['Poly', 'text-pink-900', 'polyunsaturated_fat'],
                        'monounsaturated_fat' => ['Mono', 'text-pink-950', 'monounsaturated_fat'],
                        'fiber' => ['Fibra', 'text-sky-900', 'fiber'],
                        'colesterol' => ['Colest', 'text-rose-900', 'colesterol']
                    ];
                @endphp

                @foreach($nutrients_map as $key => $data)
                    <div class="bg-white/20 p-2 rounded-xl border border-black/5">
                        <p class="text-[8px] font-black uppercase {{ $data[1] }}">{{ $data[0] }}</p>
                        <div class="flex flex-col">
                            <span class="text-xs font-black text-gray-900">{{ round($totals[$key] ?? 0, 1) }}</span>
                            <span class="text-[7px] font-bold text-gray-900/40">/ {{ round($goals->{$data[2]} ?? 0) }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>