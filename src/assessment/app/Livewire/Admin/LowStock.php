<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Product;

class LowStock extends Component
{
    public $products;

    public function mount()
    {
        $this->products = Product::where('stock_quantity','<=','restock_quantity')->get();
    }

    public function render()
    {
        return view('livewire.admin.low-stock');
    }
}
