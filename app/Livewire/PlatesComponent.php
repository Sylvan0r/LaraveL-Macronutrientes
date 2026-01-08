<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Plato;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class PlatesComponent extends Component
{
    public $showCreate = false;
    public $name, $descripcion;
    public $selectedProducts = [];
    public $quantities = [];

    public $userProducts = [];
    public $publicProducts = [];

    public function mount() {
        $this->loadLists();
    }

    public function loadLists() {
        $this->publicProducts = Product::whereNull('id_user')->orderByDesc('is_favorite')->orderBy('name')->get();
        $this->userProducts = Product::where('id_user', Auth::id())->orderByDesc('is_favorite')->orderBy('name')->get();
    }

    public function openCreate() { $this->showCreate = true; }

    public function closeCreate() {
        $this->showCreate = false;
        $this->reset(['name', 'descripcion', 'selectedProducts', 'quantities']);
    }

    // Favorito para el Plato (Card principal)
    public function toggleFavorite($platoId) {
        $plato = Plato::where('id', $platoId)->where('user_id', Auth::id())->first();
        if ($plato) $plato->update(['is_favorite' => !$plato->is_favorite]);
    }

    // Favorito para el Producto (Dentro del modal)
    public function toggleFavoriteProduct($productId) {
        $product = Product::find($productId);
        if ($product) {
            $product->update(['is_favorite' => !$product->is_favorite]);
            $this->loadLists();
        }
    }

    public function createPlato() {
        $this->validate([
            'name' => 'required|min:3',
            'selectedProducts' => 'required|array|min:1'
        ]);

        $plato = Plato::create([
            'name' => $this->name,
            'descripcion' => $this->descripcion,
            'user_id' => Auth::id(),
        ]);

        foreach ($this->selectedProducts as $productId) {
            $qty = $this->quantities[$productId] ?? 100;
            $plato->products()->attach($productId, ['quantity' => $qty]);
        }

        $this->closeCreate();
        session()->flash('success', '¡Plato guardado con éxito!');
    }

    public function deletePlate($platoId) {
        $plato = Plato::where('id', $platoId)->where('user_id', Auth::id())->first();
        if ($plato) {
            $plato->products()->detach();
            $plato->delete();
            session()->flash('success', 'Plato eliminado correctamente.');
        }
    }

    public function render() {
        return view('livewire.plates-component', [
            'userPlates' => Plato::where('user_id', Auth::id())
                ->with('products')
                ->orderByDesc('is_favorite')
                ->get(),
        ]);
    }
}