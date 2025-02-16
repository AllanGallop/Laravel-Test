<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;

class Products extends Component
{
    use WithPagination;

    public $name, $price, $stock_quantity, $restock_quantity, $product_id;
    public $isEdit = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'price' => 'required|numeric|min:0',
        'stock_quantity' => 'required|integer|min:0',
        'restock_quantity' => 'required|integer|min:0',
    ];

    public function render()
    {
        return view('livewire.admin.products', [
            'products' => Product::orderBy('name')->paginate(10),
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
        Product::create($this->only(['name', 'price', 'stock_quantity', 'restock_quantity']));

        session()->flash('message', 'Product added successfully!');
        $this->reset();
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $this->product_id = $id;
        $this->name = $product->name;
        $this->price = $product->price;
        $this->stock_quantity = $product->stock_quantity;
        $this->restock_quantity = $product->restock_quantity;
        $this->isEdit = true;
    }

    public function update()
    {
        $this->validate();

        Product::findOrFail($this->product_id)->update(
            $this->only(['name', 'price', 'stock_quantity', 'restock_quantity'])
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