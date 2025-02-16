<div class="admin-container">
    <h2>Low Stock Products</h2>

    <table>
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Available</th>
                <th>Restock</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->stock_quantity }}</td>
                    <td>{{ $product->restock_quantity }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>