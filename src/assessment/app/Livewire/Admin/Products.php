<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;

class Products extends Component
{
    use WithPagination;

    public $name, $description, $price, $stock_level, $reorder_stock_level, $product_id;
    public $isEdit = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'price' => 'required|numeric|min:0',
        'stock_level' => 'required|integer|min:0',
        'reorder_stock_level' => 'required|integer|min:0',
    ];

    public function render()
    {
        return view('livewire.admin.products', [
            'products' => Product::latest()->paginate(10),
        ]);
    }

    public function create()
    {
        $this->reset();
        $this->isEdit = false;
    }

    public function store()
    {
        $this->validate();
        Product::create($this->only(['name', 'description', 'price', 'stock_level', 'reorder_stock_level']));

        session()->flash('message', 'Product added successfully!');
        $this->reset();
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $this->product_id = $id;
        $this->name = $product->name;
        $this->description = $product->description;
        $this->price = $product->price;
        $this->stock_level = $product->stock_level;
        $this->reorder_stock_level = $product->reorder_stock_level;
        $this->isEdit = true;
    }

    public function update()
    {
        $this->validate();

        Product::findOrFail($this->product_id)->update(
            $this->only(['name', 'description', 'price', 'stock_level', 'reorder_stock_level'])
        );

        session()->flash('message', 'Product updated successfully!');
        $this->reset();
    }

    public function delete($id)
    {
        Product::findOrFail($id)->delete();
        session()->flash('message', 'Product deleted successfully!');
    }
}