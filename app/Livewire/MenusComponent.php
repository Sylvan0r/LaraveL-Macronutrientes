<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Plato;
use App\Models\Menu;
use Illuminate\Support\Facades\Auth;

class MenusComponent extends Component
{
    public $showCreate = false;
    public $name;
    public $selectedPlatos = [];
    public $quantities = []; // Guardará las raciones por plato ID

    public function togglePlatoFavorite($platoId)
    {
        $plato = Plato::where('id', $platoId)->where('user_id', Auth::id())->first();
        if ($plato) {
            $plato->update(['is_favorite' => !$plato->is_favorite]);
        }
    }

    public function openCreate() 
    { 
        $this->reset(['name', 'selectedPlatos', 'quantities']);
        $this->showCreate = true; 
    }

    public function closeCreate() 
    {
        $this->showCreate = false;
    }

    public function createMenu()
    {
        $this->validate([
            'name' => 'required|string|min:3',
            'selectedPlatos' => 'required|array|min:1',
        ]);

        $menu = Menu::create([
            'name' => $this->name,
            'user_id' => Auth::id(),
        ]);

        foreach ($this->selectedPlatos as $platoId) {
            // Si no se especificó cantidad, por defecto es 1 ración
            $qty = (isset($this->quantities[$platoId]) && $this->quantities[$platoId] > 0) 
                   ? $this->quantities[$platoId] : 1;

            $menu->platos()->attach($platoId, ['quantity' => $qty]);
        }

        $this->closeCreate();
        session()->flash('success', 'Menú "' . $menu->name . '" creado con éxito.');
    }

    public function deleteMenu($menuId)
    {
        $menu = Menu::where('id', $menuId)->where('user_id', Auth::id())->first();
        if ($menu) {
            $menu->platos()->detach();
            $menu->delete();
            session()->flash('success', 'Menú eliminado.');
        }
    }

    public function render()
    {
        return view('livewire.menus-component', [
            'userPlatos' => Plato::where('user_id', Auth::id())
                                ->orderByDesc('is_favorite')
                                ->orderBy('name')
                                ->get(),
            'userMenus' => Menu::where('user_id', Auth::id())
                                ->with('platos')
                                ->latest()
                                ->get(),
        ]);
    }
}