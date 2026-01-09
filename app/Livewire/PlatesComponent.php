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
    public $selectedIngredients = [];
    public $tempQuantities = []; 

    public function openCreate() { 
        $this->reset(['name', 'descripcion', 'selectedIngredients', 'tempQuantities']);
        $this->showCreate = true; 
    }

    public function closeCreate() { $this->showCreate = false; }

    public function addProduct($productId) {
        $product = Product::find($productId);
        if (!$product) return;

        // Validamos que exista una cantidad, si no, por defecto 100g
        $qty = (isset($this->tempQuantities[$productId]) && $this->tempQuantities[$productId] > 0) 
               ? $this->tempQuantities[$productId] : 100;

        $this->selectedIngredients[] = [
            'id' => $product->id,
            'name' => $product->name,
            'quantity' => $qty,
            'calories' => $product->calories,
            'proteins' => $product->proteins,
            'carbohydrates' => $product->carbohydrates,
            'total_fat' => $product->total_fat,
            'saturated_fat' => $product->saturated_fat,
            'trans_fat' => $product->trans_fat,
            'monounsaturated_fat' => $product->monounsaturated_fat,
            'polyunsaturated_fat' => $product->polyunsaturated_fat,
            'fiber' => $product->fiber,
            'colesterol' => $product->colesterol,
        ];
        
        // Limpiamos el input de ese producto específico
        unset($this->tempQuantities[$productId]);
    }

    public function removeIngredient($index) {
        unset($this->selectedIngredients[$index]);
        $this->selectedIngredients = array_values($this->selectedIngredients);
    }

    public function toggleFavorite($platoId) {
        $plato = Plato::where('id', $platoId)->where('user_id', Auth::id())->first();
        if ($plato) { $plato->update(['is_favorite' => !$plato->is_favorite]); }
    }

    public function deletePlate($platoId) {
        $plato = Plato::where('id', $platoId)->where('user_id', Auth::id())->first();
        if ($plato) {
            $plato->products()->detach();
            $plato->delete();
            session()->flash('success', 'Plato eliminado correctamente.');
        }
    }

    public function createPlato() {
        $this->validate([
            'name' => 'required|min:3',
            'selectedIngredients' => 'required|array|min:1'
        ], [
            'name.required' => 'Debes ponerle un nombre al plato.',
            'selectedIngredients.required' => 'Debes añadir al menos un ingrediente.'
        ]);

        try {
            $plato = Plato::create([
                'name' => $this->name,
                'descripcion' => $this->descripcion,
                'user_id' => Auth::id(),
                'is_favorite' => false
            ]);

            foreach ($this->selectedIngredients as $item) {
                $plato->products()->attach($item['id'], ['quantity' => $item['quantity']]);
            }

            $this->closeCreate();
            session()->flash('success', '¡Receta guardada con éxito!');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Error en la base de datos: ' . $e->getMessage());
        }
    }

    public function render() {
        return view('livewire.plates-component', [
            'userPlates' => Plato::where('user_id', Auth::id())->with('products')->orderByDesc('is_favorite')->orderByDesc('created_at')->get(),
            'myProducts' => Product::where('id_user', Auth::id())->orderBy('name')->get(),
            'publicProducts' => Product::whereNull('id_user')->orderBy('name')->get(),
        ]);
    }
}