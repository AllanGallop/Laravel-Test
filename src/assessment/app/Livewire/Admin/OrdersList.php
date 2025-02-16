<?php

namespace App\Livewire\Admin;

use App\Models\Order;
use Livewire\Component;

class OrdersList extends Component
{
    public $orders;

    public function mount()
    {
        $this->orders = Order::with('user')->get();
    }

    public function selectOrder($orderId)
    {
        $this->dispatch('orderSelected', $orderId);
    }

    public function render()
    {
        return view('livewire.admin.orders-list');
    }
}
