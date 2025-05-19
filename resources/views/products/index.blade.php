@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">All Products</h1>

    {{-- Search Form --}}
    <form method="GET" action="{{ route('admin.products.search') }}" class="row mb-4">
    <div class="col-md-4">
        <input
            type="text"
            name="query"
            class="form-control"
            placeholder="Search by name or category"
            value="{{ request('query') }}"
        >
    </div>

    <div class="col-md-2 form-check ms-2 mt-2">
        <input
            type="checkbox"
            class="form-check-input"
            id="expensive_only"
            name="expensive_only"
            value="1"
            {{ request('expensive_only') ? 'checked' : '' }}
        >
        <label class="form-check-label" for="expensive_only">
            Expensive Only
        </label>
    </div>

    <div class="col-md-2 mt-2">
        <button class="btn btn-primary" type="submit">Search</button>
    </div>
</form>


    {{-- Product List --}}
    @if($products->count())
        <div class="list-group mb-4">
            @foreach($products as $product)
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-1">{{ $product->name }}</h5>
                        <p class="mb-1">
                            Category: <strong>{{ $product->category->name ?? 'N/A' }}</strong><br>
                            Price: <strong>${{ number_format($product->price, 2) }}</strong>
                        </p>
                    </div>
                    
                    {{-- Stock Status Toggle --}}
                    <form action="{{ route('admin.products.stock', $product->id) }}" method="POST" class="text-end">
                        @csrf
                        <div class="form-check form-switch">
                            <input 
                                class="form-check-input" 
                                type="checkbox" 
                                name="in_stock" 
                                value="1" 
                                onchange="this.form.submit()" 
                                {{ $product->in_stock ? 'checked' : '' }}
                            >
                            <label class="form-check-label">
                                {{ $product->in_stock ? 'In Stock' : 'Out of Stock' }}
                            </label>
                        </div>
                    </form>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div>
            {{ $products->withQueryString()->links('pagination::bootstrap-5') }}
        </div>
    @else
        <div class="alert alert-info">No products found.</div>
    @endif
</div>
@endsection
