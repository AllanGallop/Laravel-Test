<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Product;

/**
 * Class LowStock
 *
 * Livewire component to list products that have low stock levels.
 * Displays products whose stock quantity is less than or equal to their restock quantity.
 *
 * @package App\Livewire\Admin
 * @property \Illuminate\Database\Eloquent\Collection $products A collection of products with low stock levels.
 */
class LowStock extends Component
{
    /**
     * The collection of products with low stock levels.
     *
     * @var \Illuminate\Database\Eloquent\Collection
     */
    public $products;

    /**
     * Mount the component.
     * Retrieves products where the stock quantity is less than or equal to the restock quantity.
     *
     * @return void
     */
    public function mount()
    {
        $this->products = Product::where('stock_quantity','<=','restock_quantity')->get();
    }

    /**
     * Render the Livewire component view.
     * 
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.admin.low-stock');
    }
}
