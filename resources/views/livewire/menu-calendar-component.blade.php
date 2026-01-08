<div class="p-4 bg-gray-800 text-white rounded-lg h-full">
    <h2 class="text-yellow-400 text-2xl mb-4">Calendario de Menús</h2>

    <input type="date" wire:model="selectedDate"
           class="p-2 rounded text-black mb-4"
           wire:change="loadCalendar">

    <h3 class="text-lg mb-2">Menús disponibles</h3>
    <ul class="mb-4 space-y-2">
        @foreach($menus as $menu)
            <li class="flex justify-between items-center bg-gray-700 p-2 rounded">
                <span>{{ $menu->name }}</span>
                <button wire:click="addMenuToDate({{ $menu->id }})"
                        class="bg-green-600 px-2 py-1 rounded">Añadir</button>
            </li>
        @endforeach
    </ul>

    <h3 class="text-lg mb-2">Menús del día {{ $selectedDate }}</h3>
    <ul class="mb-4 space-y-2">
        @foreach($calendarMenus as $menuDay)
            <li class="bg-gray-700 p-2 rounded">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="font-semibold">{{ $menuDay->menu->name }}</p>
                        @foreach($menuDay->menu->platos as $plato)
                            <div class="flex items-center gap-2 text-sm">
                                <span>{{ $plato->name }} (x{{ $plato->pivot->quantity }})</span>
                            </div>
                        @endforeach
                    </div>

                    <button wire:click="removeMenuFromDate({{ $menuDay->id }})"
                            class="bg-red-600 px-2 py-1 rounded">Eliminar</button>
                </div>
            </li>
        @endforeach
    </ul>

    @if($goals)
        <h3 class="text-lg mb-2">Totales del día</h3>
        <ul>
            @foreach($totals as $key => $value)
                <li>
                    {{ ucfirst($key) }}: {{ $value }} / {{ $goals->$key }} — <strong>{{ $alerts[$key] }}</strong>
                </li>
            @endforeach
        </ul>
    @endif
</div>