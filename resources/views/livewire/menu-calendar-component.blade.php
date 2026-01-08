<div class="flex flex-col h-full space-y-6">
    {{-- Selector de Fecha --}}
    <div class="relative group">
        <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest ml-4 mb-1 block text-gray-900">Fecha de Consulta</label>
        <input type="date" wire:model="selectedDate" wire:change="loadCalendar"
               class="w-full bg-gray-900 border-2 border-gray-800 rounded-2xl p-4 text-white font-black text-sm focus:border-yellow-400 transition-all outline-none">
    </div>

    {{-- Menús Disponibles --}}
    <div class="space-y-3">
        <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] flex items-center gap-2">
            <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span> Biblioteca de Menús
        </h3>
        <div class="max-h-48 overflow-y-auto custom-scrollbar space-y-2 pr-2">
            @foreach($menus as $menu)
                <div class="flex justify-between items-center bg-gray-900/50 hover:bg-gray-700/50 p-3 rounded-xl border border-gray-800 transition-all group">
                    <span class="text-xs font-bold text-gray-300 group-hover:text-white">{{ $menu->name }}</span>
                    <button wire:click="addMenuToDate({{ $menu->id }})"
                            class="text-[10px] font-black bg-emerald-600/10 text-emerald-400 border border-emerald-600/30 px-3 py-1 rounded-lg hover:bg-emerald-600 hover:text-white transition-all">
                        AÑADIR
                    </button>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Menús Planificados --}}
    <div class="flex-1 space-y-3">
        <h3 class="text-[10px] font-black text-yellow-400 uppercase tracking-[0.2em]">Plan para Hoy</h3>
        <div class="space-y-4">
            @forelse($calendarMenus as $menuDay)
                <div class="bg-gray-900 border-l-4 border-yellow-400 p-4 rounded-r-2xl shadow-lg relative group">
                    <button wire:click="removeMenuFromDate({{ $menuDay->id }})"
                            class="absolute top-2 right-2 text-gray-600 hover:text-rose-500 transition-colors">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"/></svg>
                    </button>
                    
                    <p class="font-black text-white text-sm uppercase tracking-tight truncate pr-6">{{ $menuDay->menu->name }}</p>
                    <div class="mt-2 space-y-1">
                        @foreach($menuDay->menu->platos as $plato)
                            <p class="text-[10px] text-gray-500 font-bold flex items-center gap-1">
                                <span class="text-yellow-400">●</span> {{ $plato->name }} 
                                <span class="text-gray-700 italic">x{{ $plato->pivot->quantity }}</span>
                            </p>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="text-center py-6 border-2 border-dashed border-gray-800 rounded-3xl">
                    <p class="text-[10px] font-black text-gray-700 uppercase">Sin actividades</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Resumen de Totales (Footer del calendario) --}}
    @if($goals)
        <div class="bg-yellow-400 rounded-2xl p-4 shadow-xl shadow-yellow-400/10">
            <h4 class="text-[10px] font-black text-gray-900 uppercase tracking-widest mb-2">Balance del Día</h4>
            <div class="grid grid-cols-2 gap-2">
                @foreach($totals as $key => $value)
                    <div class="bg-gray-900/10 p-2 rounded-lg border border-gray-900/5">
                        <p class="text-[9px] font-black text-gray-800 uppercase leading-none">{{ $key }}</p>
                        <p class="text-xs font-black text-gray-900">{{ $value }} <span class="text-[8px] opacity-60">/ {{ $goals->$key }}</span></p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>