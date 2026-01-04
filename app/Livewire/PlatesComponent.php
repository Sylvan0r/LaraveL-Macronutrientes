<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Plato;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class PlatesComponent extends Component
{
    public $showCreate = false;

    public $name;
    public $descripcion;

    public $selectedProducts = [];
    public $quantities = [];

    public $publicProducts = [];
    public $userProducts = [];

    protected $listeners = ['deletePlate'];

    public function mount()
    {
        $this->loadProducts();
    }

    /* ---------------------------
        CARGA DE PRODUCTOS
    --------------------------- */
    public function loadProducts()
    {
        $this->publicProducts = Product::whereNull('id_user')
            ->orderByDesc('is_favorite')
            ->orderBy('name')
            ->get();

        $this->userProducts = Product::where('id_user', Auth::id())
            ->orderByDesc('is_favorite')
            ->orderBy('name')
            ->get();
    }

    /* ---------------------------
        MODAL
    --------------------------- */
    public function openCreate()
    {
        $this->showCreate = true;
    }

    public function closeCreate()
    {
        $this->showCreate = false;
        $this->resetFields();
    }

    /* ---------------------------
        VALIDACIÓN
    --------------------------- */
    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'selectedProducts' => 'required|array|min:1',
            'quantities' => 'required|array',
        ];
    }

    /* ---------------------------
        CREAR PLATO
    --------------------------- */
    public function createPlato()
    {
        $this->validate();

        $plato = Plato::create([
            'name' => $this->name,
            'descripcion' => $this->descripcion,
            'user_id' => Auth::id(),
            'is_favorite' => false,
        ]);

        foreach ($this->selectedProducts as $productId) {
            $plato->products()->attach($productId, [
                'quantity' => $this->quantities[$productId] ?? 1
            ]);
        }

        session()->flash('success', 'Plato creado correctamente.');

        $this->closeCreate();
        $this->loadProducts();
    }

    /* ---------------------------
        ELIMINAR PLATO
    --------------------------- */
    public function deletePlate($platoId)
    {
        $plato = Plato::where('id', $platoId)
            ->where('user_id', Auth::id())
            ->first();

        if ($plato) {
            $plato->products()->detach();
            $plato->delete();

            session()->flash('success', 'Plato eliminado correctamente.');
        }
    }

    /* ---------------------------
        FAVORITOS (PLATOS)
    --------------------------- */
    public function togglePlateFavorite($platoId)
    {
        $plato = Plato::where('id', $platoId)
            ->where('user_id', Auth::id())
            ->first();

        if ($plato) {
            $plato->update([
                'is_favorite' => !$plato->is_favorite
            ]);
        }
    }

    /* ---------------------------
        FAVORITOS (PRODUCTOS)
    --------------------------- */
    public function toggleFavoriteProduct($productId)
    {
        $product = Product::find($productId);

        if ($product && ($product->id_user === null || $product->id_user === Auth::id())) {
            $product->update([
                'is_favorite' => !$product->is_favorite
            ]);
            $this->loadProducts();
        }
    }

    public function resetFields()
    {
        $this->reset(['name', 'descripcion', 'selectedProducts', 'quantities']);
    }

    /* ---------------------------
        RENDER
    --------------------------- */
    public function render()
    {
        $userPlates = Plato::where('user_id', Auth::id())
            ->orderByDesc('is_favorite')
            ->orderBy('name')
            ->with('products.category')
            ->get();

        return view('livewire.plates-component', [
            'userPlates' => $userPlates,
        ]);
    }
}