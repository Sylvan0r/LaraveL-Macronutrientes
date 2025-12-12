<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Menu;
use App\Models\Plato;
use Illuminate\Support\Facades\Auth;

class MenusComponent extends Component
{
    public $showCreate = false;
    public $name;
    public $selectedPlatos = [];
    public $quantities = [];

    public $userPlatos = [];
    public $menus = [];

    public function mount()
    {
        $this->loadUserPlatos();
        $this->loadMenus();
    }

    public function loadUserPlatos()
    {
        // Trae todos los platos del usuario con sus productos
        $this->userPlatos = Plato::where('user_id', Auth::id())
            ->with('products.category')
            ->get();
    }

    public function loadMenus()
    {
        $this->menus = Menu::where('user_id', Auth::id())
            ->with('platos.products.category')
            ->get();
    }

    public function openCreate()
    {
        $this->showCreate = true;
    }

    public function closeCreate()
    {
        $this->showCreate = false;
        $this->reset(['name','selectedPlatos','quantities']);
    }

    public function createMenu()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'selectedPlatos' => 'required|array|min:1',
            'quantities' => 'required|array'
        ]);

        $menu = Menu::create([
            'name' => $this->name,
            'user_id' => Auth::id()
        ]);

        foreach ($this->selectedPlatos as $platoId) {
            $menu->platos()->attach($platoId, [
                'quantity' => $this->quantities[$platoId] ?? 1
            ]);
        }

        session()->flash('success', 'Menú creado correctamente.');

        // Cerrar overlay y recargar listas
        $this->closeCreate();
        $this->loadMenus();
    }

    public function deleteMenu($menuId)
    {
        $menu = Menu::find($menuId);
        if ($menu && $menu->user_id === Auth::id()) {
            $menu->delete();
            $this->loadMenus();
            session()->flash('success', 'Menú eliminado correctamente.');
        }
    }

    public function render()
    {
        return view('livewire.menus-component', [
            'userPlatos' => $this->userPlatos,
            'menus' => $this->menus
        ]);
    }
}