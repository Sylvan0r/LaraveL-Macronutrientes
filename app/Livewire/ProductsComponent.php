<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Category;

class ProductsComponent extends Component
{
    // Lista de productos para el select (si quieres)
    public $products = [];

    // Crear (overlay)
    public $showCreate = false;

    // Campos del formulario
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

    // Categories para el select
    public $categories = [];

    public function mount()
    {
        $this->loadProducts();
        $this->categories = Category::orderBy('name')->get();
    }

    public function loadProducts()
    {
        // Si quieres cargar todos los productos (p. ej. para el select ya existente)
        $this->products = Product::orderBy('name')->get();
    }

    // Abrir / cerrar overlay
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
            'calories' => 'nullable|numeric',
            'total_fat' => 'nullable|numeric',
            'saturated_fat' => 'nullable|numeric',
            'trans_fat' => 'nullable|numeric',
            'colesterol' => 'nullable|numeric',
            'polyunsaturated_fat' => 'nullable|numeric',
            'monounsaturated_fat' => 'nullable|numeric',
            'carbohydrates' => 'nullable|numeric',
            'fiber' => 'nullable|numeric',
            'proteins' => 'nullable|numeric',
            'category_id' => 'nullable|exists:categories,id',
            'external_id' => 'nullable|string|max:255',
        ];
    }

    public function createProduct()
    {
        $this->validate();

        // Asegurarse de que hay un usuario autenticado
        $userId = Auth::id();
        if (!$userId) {
            session()->flash('error', 'No hay usuario autenticado.');
            return;
        }

        // Crear producto e inyectar id_user explícitamente
        $product = Product::create([
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
            'id_user' => $userId, // <-- aquí aseguramos el id del usuario
        ]);

        // Recargar lista y cerrar overlay
        $this->loadProducts();
        $this->closeCreate();

        session()->flash('success', 'Producto creado correctamente.');
    }

    public function resetFields()
    {
        $this->reset([
            'name','calories','total_fat','saturated_fat','trans_fat',
            'colesterol','polyunsaturated_fat','monounsaturated_fat',
            'carbohydrates','fiber','proteins','category_id','external_id'
        ]);
    }

    public function render()
    {
        return view('livewire.products-component');
    }
}
