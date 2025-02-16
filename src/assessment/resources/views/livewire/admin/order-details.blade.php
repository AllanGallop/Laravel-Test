<div class="admin-container">
    @if($order)
        <h2>Order Details - Order #{{ $order->id }}</h2>

        <p><strong>User:</strong> {{ $order->user->name }}</p>

        <h3>Products</h3>
        <ul>
            @foreach($order->orderItems as $product)
                <li>{{ $product->name }} - {{ $product->quantity }} x ${{ $product->price }}</li>
            @endforeach
        </ul>

        <h3>Order Status</h3>
        <select wire:model="status">
            <option value="pending">Pending</option>
            <option value="processing">Processing</option>
            <option value="completed">Completed</option>
            <option value="cancelled">Cancelled</option>
        </select>

        <button wire:click="updateStatus">Update Status</button>

        @if (session()->has('message'))
            <div class="alert alert-success">{{ session('message') }}</div>
        @endif
    @else
        <p>Select an order to view details.</p>
    @endif
</div>
