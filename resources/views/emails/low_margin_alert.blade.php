<h2>Low Margin Alert</h2>

<p>The following products are below their preferred margin:</p>

<ul>
    @foreach ($products as $product)
    <li><strong>{{ $product['name'] }}</strong>: Margin {{ $product['margin'] }}% (Preferred margin: {{ $product['threshold'] }}%)</li>
    @endforeach
</ul>

<p>Please take necessary action.</p>