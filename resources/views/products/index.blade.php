@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>All Products</h1>

        @php
            $expensive = DB::select("SELECT * FROM products WHERE price > 100");
        @endphp

        <ul>
            @foreach($products as $product)
                <li>
                    {{ $product['name'] }} -
                    ${{ $product['price'] }} -
                    {{ $product['category'] }}
                </li>
            @endforeach
        </ul>
    </div>
@endsection
