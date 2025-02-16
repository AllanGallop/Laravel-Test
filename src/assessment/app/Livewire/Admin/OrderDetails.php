<?php

namespace App\Livewire\Admin;

use App\Models\Order;
use Livewire\Component;

class OrderDetails extends Component
{
    public $order;
    public $status;

    protected $listeners = ['orderSelected' => 'loadOrder'];

    public function loadOrder($orderId)
    {
        $this->order = Order::with('orderItems', 'user')->find($orderId);
        $this->status = $this->order->status;
    }

    public function updateStatus()
    {
        $this->order->update(['status' => $this->status]);
        session()->flash('message', 'Order status updated successfully.');
    }

    public function render()
    {
        return view('livewire.admin.order-details');
    }
}
