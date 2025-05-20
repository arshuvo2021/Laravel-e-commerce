<html>
<body>
<h1>Products List</h1>
<ul>
@foreach ($products as $product)
    <li>{{ $product->id }} - {{ $product->name }} - ${{ $product->price }} - {{ $product->in_stock ? 'Yes' : 'No' }}</li>
@endforeach
</ul>
</body>
</html>
