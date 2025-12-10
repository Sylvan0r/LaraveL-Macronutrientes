<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Plato;
use App\Models\Product;

class PlatesComponent extends Component
{
    public $showCreate = false;

    public $name;
    public $descripcion;

    // Array de productos seleccionados
    public $selectedProducts = []; // id de productos
    public $quantities = [];       // cantidad de cada producto

    // Lista de productos públicos o del usuario
    public $products = [];

    public function mount()
    {
        $this->loadProducts();
    }

    public function loadProducts()
    {
        $this->products = Product::whereNull('id_user')
                            ->orWhere('id_user', Auth::id())
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

    public function resetFields()
    {
        $this->reset(['name', 'descripcion', 'selectedProducts', 'quantities']);
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'selectedProducts' => 'required|array|min:1',
            'selectedProducts.*' => 'exists:products,id',
            'quantities' => 'required|array',
            'quantities.*' => 'numeric|min:0',
        ];
    }

    public function createPlato()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'selectedProducts' => 'required|array',
            'quantities' => 'required|array',
        ]);

        $userId = auth()->id();

        $plato = Plato::create([
            'name' => $this->name,
            'descripcion' => $this->descripcion,
            'user_id' => $userId,
        ]);

        // Guardar productos con cantidad en la tabla pivote
        foreach ($this->selectedProducts as $productId) {
            $plato->products()->attach($productId, [
                'quantity' => $this->quantities[$productId] ?? 1
            ]);
        }

        session()->flash('success', 'Plato creado correctamente.');

        // Resetear campos
        $this->reset(['name', 'descripcion', 'selectedProducts', 'quantities']);
    }

    public function deletePlato($id)
    {
        $plato = Plato::where('user_id', Auth::id())->find($id);
        if ($plato) {
            $plato->delete();
            session()->flash('success', 'Plato eliminado.');
        }
    }

    public function render()
    {
        // Cargar platos del usuario
        $platos = Plato::with('products')->where('user_id', Auth::id())->get();

        return view('livewire.plates-component', compact('platos'));
    }
}