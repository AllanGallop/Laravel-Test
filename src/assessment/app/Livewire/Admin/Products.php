<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;

/**
 * Class Products
 *
 * Livewire component to manage products in the admin panel.
 * Allows creating, editing, updating, and deleting products.
 *
 * @package App\Livewire\Admin
 * @property string $name The name of the product.
 * @property float $price The price of the product.
 * @property int $stock_quantity The quantity of the product in stock.
 * @property int $restock_quantity The quantity of the product to be restocked.
 * @property int $product_id The ID of the product currently being edited.
 * @property bool $isEdit Whether the component is in edit mode or not.
 * @property array $rules Validation rules for the product fields.
 */
class Products extends Component
{
    use WithPagination;

    /**
     * The name, price, stock quantity, restock quantity, and product ID fields for the product.
     *
     * @var string
     * @var float
     * @var int
     * @var int
     * @var int
     */
    public $name, $price, $stock_quantity, $restock_quantity, $product_id;
    public $isEdit = false;

    /**
     * Validation rules for the product fields.
     *
     * @var array
     */
    protected $rules = [
        'name' => 'required|string|max:255',
        'price' => 'required|numeric|min:0',
        'stock_quantity' => 'required|integer|min:0',
        'restock_quantity' => 'required|integer|min:0',
    ];

    /**
     * Render the Livewire component view with paginated products.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.admin.products', [
            'products' => Product::orderBy('name')->paginate(10),
        ]);
    }

    /**
     * Initialize the component for creating a new product.
     * Resets all fields and sets the edit flag to false.
     *
     * @return void
     */
    public function create()
    {
        $this->reset();
        $this->isEdit = false;
    }

    /**
     * Store a new product in the database.
     * Validates input fields and creates the new product.
     * 
     * @return void
     */
    public function store()
    {
        $this->validate();
        Product::create($this->only(['name', 'price', 'stock_quantity', 'restock_quantity']));

        session()->flash('message', 'Product added successfully!');
        $this->reset();
    }

    /**
     * Retrieve the product to be edited and populate the form with its data.
     * 
     * @param int $id The ID of the product to edit.
     * @return void
     */
    public function edit($id): void
    {
        $product = Product::findOrFail($id);
        $this->product_id = $id;
        $this->name = $product->name;
        $this->price = $product->price;
        $this->stock_quantity = $product->stock_quantity;
        $this->restock_quantity = $product->restock_quantity;
        $this->isEdit = true;
    }

    /**
     * Update the product with the provided data.
     * Validates input fields and updates the existing product in the database.
     *
     * @return void
     */
    public function update(): void
    {
        $this->validate();

        Product::findOrFail($this->product_id)->update(
            $this->only(['name', 'price', 'stock_quantity', 'restock_quantity'])
        );

        session()->flash('message', 'Product updated successfully!');
        $this->reset();
    }

    /**
     * Delete the specified product.
     *
     * @param int $id The ID of the product to delete.
     * @return void
     */
    public function delete(int $id): void
    {
        Product::findOrFail($id)->delete();
        session()->flash('message', 'Product deleted successfully!');
    }
}
