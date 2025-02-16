<?php

namespace App\Livewire\Admin;

use App\Models\Order;
use Livewire\Component;

/**
 * Class OrdersList
 *
 * Livewire component to list orders with associated user details.
 * Allows the admin to select an order, triggering the display of its details.
 *
 * @package App\Livewire\Admin
 * @property \Illuminate\Database\Eloquent\Collection $orders The collection of orders to be listed.
 */
class OrdersList extends Component
{
    /**
     * The list of orders to display.
     *
     * @var \Illuminate\Database\Eloquent\Collection
     */
    public $orders;

    /**
     * Mounts the component by loading the orders with their associated user.
     *
     * @return void
     */
    public function mount()
    {
        $this->orders = Order::with('user')->get();
    }

    /**
     * Selects an order and dispatches an event to notify that an order has been selected.
     * The event contains the order ID.
     *
     * @param int $orderId The ID of the order being selected.
     * @return void
     */
    public function selectOrder($orderId)
    {
        $this->dispatch('orderSelected', $orderId);
    }

    /**
     * Renders the Livewire component view.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.admin.orders-list');
    }
}
