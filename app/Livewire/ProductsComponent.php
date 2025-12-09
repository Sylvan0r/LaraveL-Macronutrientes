<?php

namespace App\Livewire;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ProductsComponent extends Component
{
    public $products = [];

    // Campos del producto
    public $name;
    public $calories;
    public $total_fat;
    public $saturated_fat;
    public $trans_fat;
    public $colesterol;
    public $polyunsaturated_fat;
    public $monounsaturated_fat;
    public $carbohydrates;
    public $fiber;
    public $proteins;
    public $category_id;
    public $external_id;

    // Control del modal
    public $modal = false;

    public function mount()
    {
        $this->loadProducts();
    }

    public function loadProducts()
    {
        $this->products = Product::orderBy('name')->get();
    }

    public function openModal()
    {
        $this->modal = true;
    }

    public function closeModal()
    {
        $this->modal = false;
        $this->resetFields();
    }

    public function resetFields()
    {
        $this->name = null;
        $this->calories = null;
        $this->total_fat = null;
        $this->saturated_fat = null;
        $this->trans_fat = null;
        $this->colesterol = null;
        $this->polyunsaturated_fat = null;
        $this->monounsaturated_fat = null;
        $this->carbohydrates = null;
        $this->fiber = null;
        $this->proteins = null;
        $this->category_id = null;
        $this->external_id = null;
    }

    public function createProduct()
    {
        Product::create([
            'name' => $this->name,
            'calories' => $this->calories,
            'total_fat' => $this->total_fat,
            'saturated_fat' => $this->saturated_fat,
            'trans_fat' => $this->trans_fat,
            'colesterol' => $this->colesterol,
            'polyunsaturated_fat' => $this->polyunsaturated_fat,
            'monounsaturated_fat' => $this->monounsaturated_fat,
            'carbohydrates' => $this->carbohydrates,
            'fiber' => $this->fiber,
            'proteins' => $this->proteins,
            'category_id' => $this->category_id,
            'external_id' => $this->external_id,
            'id_user' => Auth::id(),
        ]);

        $this->closeModal();
        $this->loadProducts();
    }

    public function render()
    {
        return view('livewire.products-component');
    }
}
