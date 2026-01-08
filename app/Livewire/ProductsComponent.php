<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Category;

class ProductsComponent extends Component
{
    public $showCreate = false;

    // Propiedades del modelo
    public $name, $calories, $total_fat, $saturated_fat, $trans_fat, $colesterol;
    public $polyunsaturated_fat, $monounsaturated_fat, $carbohydrates, $fiber, $proteins;
    public $category_id, $external_id;

    public $categories = [];

    protected $listeners = ['deleteProduct'];

    public function mount()
    {
        $this->categories = Category::orderBy('name')->get();
    }

    public function toggleFavorite($productId)
    {
        $product = Product::where('id', $productId)->where('id_user', Auth::id())->first();
        if ($product) {
            $product->update(['is_favorite' => !$product->is_favorite]);
        }
    }

    public function openCreate() { $this->showCreate = true; }

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
            'proteins' => 'nullable|numeric',
            'carbohydrates' => 'nullable|numeric',
            'total_fat' => 'nullable|numeric',
            'category_id' => 'nullable|exists:categories,id',
        ];
    }
    public function createProduct()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'calories' => 'nullable|numeric',
            // Puedes añadir más validaciones si quieres ser estricto
        ]);

        Product::create([
            'name'                => $this->name,
            'calories'            => $this->calories,
            'total_fat'           => $this->total_fat,
            'saturated_fat'       => $this->saturated_fat,
            'trans_fat'           => $this->trans_fat,
            'monounsaturated_fat' => $this->monounsaturated_fat,
            'polyunsaturated_fat' => $this->polyunsaturated_fat,
            'colesterol'          => $this->colesterol,
            'carbohydrates'       => $this->carbohydrates,
            'fiber'               => $this->fiber,
            'proteins'            => $this->proteins,
            'category_id'         => $this->category_id,
            'id_user'             => Auth::id(),
        ]);

        $this->closeCreate();
        session()->flash('success', 'Producto "' . $this->name . '" añadido correctamente.');
    }

    public function deleteProduct($productId)
    {
        $product = Product::where('id', $productId)->where('id_user', Auth::id())->first();
        if ($product) {
            $product->delete();
            session()->flash('success', 'Producto eliminado.');
        }
    }

    public function resetFields()
    {
        $this->reset(['name','calories','total_fat','carbohydrates','proteins','category_id']);
    }

    public function render()
    {
        return view('livewire.products-component', [
            'userProducts' => Product::where('id_user', Auth::id())
                ->orderByDesc('is_favorite')
                ->orderBy('name')
                ->get()
        ]);
    }
}