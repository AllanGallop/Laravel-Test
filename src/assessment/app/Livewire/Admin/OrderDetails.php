<?php

namespace App\Livewire\Admin;

use App\Models\Order;
use Livewire\Component;

/**
 * Class OrderDetails
 *
 * Livewire component to display details for a selected order, 
 * and allow the admin to update the order's status.
 *
 * @package App\Livewire\Admin
 * @property \App\Models\Order $order The order details being displayed.
 * @property string $status The current status of the order.
 */
class OrderDetails extends Component
{
    /**
     * The order being viewed.
     *
     * @var \App\Models\Order
     */
    public $order;

    /**
     * The status of the order.
     *
     * @var string
     */
    public $status;

    /**
     * The list of event listeners for the component.
     * Listens for the 'orderSelected' event and triggers 'loadOrder'.
     *
     * @var array
     */
    protected $listeners = ['orderSelected' => 'loadOrder'];

    /**
     * Loads the order details based on the order ID.
     * Retrieves the order with related order items and user.
     *
     * @param int $orderId The ID of the order to load.
     * @return void
     */
    public function loadOrder($orderId)
    {
        $this->order = Order::with('orderItems', 'user')->find($orderId);
        $this->status = $this->order->status;
    }

    /**
     * Updates the status of the order and flashes a success message.
     *
     * @return void
     */
    public function updateStatus()
    {
        $this->order->update(['status' => $this->status]);
        session()->flash('message', 'Order status updated successfully.');
    }

    /**
     * Renders the Livewire component view.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.admin.order-details');
    }
}
