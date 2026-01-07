<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Plato;
use App\Models\Menu;
use Illuminate\Support\Facades\Auth;

class MenusComponent extends Component
{
    /* =========================
     | PROPIEDADES
     ========================= */

    public $showCreate = false;
    public $name;
    public $selectedPlatos = [];
    public $quantities = [];

    public $userPlatos = [];
    public $userMenus = [];

    protected $listeners = [
        'refreshMenus' => 'loadUserMenus',
        'deleteMenu',
    ];

    /* =========================
     | CICLO DE VIDA
     ========================= */

    public function mount()
    {
        $this->loadUserPlatos();
        $this->loadUserMenus();
    }

    /* =========================
     | CARGA DE DATOS
     ========================= */

    public function loadUserPlatos()
    {
        $this->userPlatos = Plato::where('user_id', Auth::id())
            ->orderByDesc('is_favorite') // ⭐ favoritos primero
            ->orderBy('name')
            ->with('products')
            ->get();
    }

    public function loadUserMenus()
    {
        $this->userMenus = Menu::where('user_id', Auth::id())
            ->with('platos.products')
            ->orderByDesc('created_at')
            ->get();
    }

    /* =========================
     | FAVORITOS (PLATOS)
     ========================= */

    public function togglePlatoFavorite($platoId)
    {
        $plato = Plato::where('id', $platoId)
            ->where('user_id', Auth::id())
            ->first();

        if (!$plato) return;

        $plato->update([
            'is_favorite' => !$plato->is_favorite,
        ]);

        // 🔄 Recargar listas para reordenar
        $this->loadUserPlatos();
        $this->loadUserMenus();
    }

    /* =========================
     | MODAL
     ========================= */

    public function openCreate()
    {
        $this->showCreate = true;
    }

    public function closeCreate()
    {
        $this->showCreate = false;
        $this->resetFields();
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'selectedPlatos' => 'required|array|min:1',
            'quantities' => 'required|array',
        ];
    }

    /* =========================
     | CREAR MENÚ (SIN AFECTAR OBJETIVOS)
     ========================= */

    public function createMenu()
    {
        $this->validate();

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

        session()->flash('success', 'Menú creado correctamente.');

        $this->loadUserMenus();
        $this->loadUserPlatos();

        return redirect()->route('dashboard');
    }

    /* =========================
     | ELIMINAR MENÚ
     ========================= */

    public function deleteMenu($menuId)
    {
        $menu = Menu::where('id', $menuId)
            ->where('user_id', Auth::id())
            ->with('platos')
            ->first();

        if (!$menu) return;

        $menu->platos()->detach();
        $menu->delete();

        session()->flash('success', 'Menú eliminado correctamente.');

        $this->loadUserMenus();
    }

    /* =========================
     | UTILIDADES
     ========================= */

    public function resetFields()
    {
        $this->reset([
            'name',
            'selectedPlatos',
            'quantities',
        ]);
    }

    public function render()
    {
        return view('livewire.menus-component', [
            'userPlatos' => $this->userPlatos,
            'userMenus' => $this->userMenus,
        ]);
    }
}
