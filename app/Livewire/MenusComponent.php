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
    public $quantities = [];

    protected $listeners = ['deleteMenu'];

    public function togglePlatoFavorite($platoId)
    {
        $plato = \App\Models\Plato::where('id', $platoId)
            ->where('user_id', auth()->id())
            ->first();

        if ($plato) {
            $plato->update([
                'is_favorite' => !$plato->is_favorite,
            ]);

            // Volvemos a cargar los platos para que la vista se refresque
            $this->userPlatos = \App\Models\Plato::where('user_id', auth()->id())
                ->orderByDesc('is_favorite')
                ->orderBy('name')
                ->get();
        }
    }

    public function openCreate() { $this->showCreate = true; }

    public function closeCreate() {
        $this->showCreate = false;
        $this->reset(['name', 'selectedPlatos', 'quantities']);
    }

    public function createMenu()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'selectedPlatos' => 'required|array|min:1',
        ]);

        $menu = Menu::create([
            'name' => $this->name,
            'user_id' => Auth::id(),
        ]);

        foreach ($this->selectedPlatos as $platoId) {
            $menu->platos()->attach($platoId, [
                'quantity' => $this->quantities[$platoId] ?? 1
            ]);
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
            'userPlatos' => Plato::where('user_id', Auth::id())->orderByDesc('is_favorite')->get(),
            'userMenus' => Menu::where('user_id', Auth::id())->with('platos.products')->latest()->get(),
        ]);
    }
}