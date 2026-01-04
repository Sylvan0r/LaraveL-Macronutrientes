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

    public $userProducts = [];
    public $publicProducts = [];

    protected $listeners = ['deletePlate'];

    public function mount()
    {
        $this->loadProducts();
    }

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
            'selectedProducts' => 'required|array|min:1',
            'quantities' => 'required|array',
        ];
    }

    public function createPlato()
    {
        $this->validate();

        $plato = Plato::create([
            'name' => $this->name,
            'descripcion' => $this->descripcion,
            'user_id' => Auth::id(),
        ]);

        foreach ($this->selectedProducts as $productId) {
            $plato->products()->attach($productId, ['quantity' => $this->quantities[$productId] ?? 1]);
        }

        $this->closeCreate();
        $this->loadProducts();
        session()->flash('success', 'Plato creado correctamente.');
        return redirect()->route('dashboard');
    }

    public function deletePlate($platoId)
    {
        $plato = Plato::where('id', $platoId)->where('user_id', Auth::id())->first();
        if ($plato) {
            $plato->products()->detach();
            $plato->delete();
            session()->flash('success', 'Plato eliminado correctamente.');
            $this->loadProducts();
        }
    }

    public function resetFields()
    {
        $this->reset(['name', 'descripcion', 'selectedProducts', 'quantities']);
    }

    public function toggleFavoriteProduct($productId)
    {
        $product = Product::find($productId);

        if (!$product) return;

        // Solo productos públicos o del usuario
        if ($product->id_user === null || $product->id_user === Auth::id()) {
            $product->update([
                'is_favorite' => !$product->is_favorite
            ]);

            $this->loadProducts(); // recargar lista para reordenar
        }
    }

    public function render()
    {
        $userPlates = Plato::where('user_id', Auth::id())
            ->with('products')
            ->get();

        return view('livewire.plates-component', [
            'userPlates' => $userPlates,
        ]);
    }
}