@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="flex">
            <div class="w-1/3">
                <livewire:admin.orders-list>
            </div>
            <div class="w-2/3">
                <livewire:admin.order-details>
            </div>
        </div>
    </div>
@endsection
