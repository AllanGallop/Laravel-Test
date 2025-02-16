<div class="admin-container">
    <h2>Manage Products</h2>

    @if (session()->has('message'))
        <div class="alert">{{ session('message') }}</div>
    @endif

    <!-- Product Form -->
    <div>
        <h4>{{ $isEdit ? 'Edit Product' : 'Add Product' }}</h4>
        <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}">
            <div>
                <label>Name</label>
                <input type="text" class="form-control" wire:model="name">
                @error('name') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div>
                <label>Price</label>
                <input type="number" step="0.01" class="form-control" wire:model="price">
                @error('price') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div>
                <label>Stock Level</label>
                <input type="number" class="form-control" wire:model="stock_quantity">
                @error('stock_quantity') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div>
                <label>Reorder Stock Level</label>
                <input type="number" class="form-control" wire:model="restock_quantity">
                @error('restock_quantity') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="btn btn-primary">{{ $isEdit ? 'Update' : 'Add' }} Product</button>
        </form>
    </div>

    <!-- Product List -->
    <table class="table w-full">
        <thead>
            <tr>
                <th>Name</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Reorder Level</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>${{ number_format($product->price, 2) }}</td>
                    <td>{{ $product->stock_quantity }}</td>
                    <td>{{ $product->restock_quantity }}</td>
                    <td>
                        <button wire:click="edit({{ $product->id }})" class="btn btn-warning btn-sm">Edit</button>
                        <button wire:click="delete({{ $product->id }})" class="btn btn-danger btn-sm">Delete</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $products->links() }} {{-- Pagination --}}
</div>
