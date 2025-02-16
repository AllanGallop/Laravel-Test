<div>
    <h2>Order List</h2>

    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>User</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
                <tr wire:click="selectOrder({{ $order->id }})">
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->user->name }}</td>
                    <td>{{ $order->status }}</td>
                    <td>
                        <button wire:click="selectOrder({{ $order->id }})">View Details</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
