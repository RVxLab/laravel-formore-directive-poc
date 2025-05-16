<h1><code>@@formore</code> Directive PoC</h1>

<h2>With 5 Items</h2>

<ul>
    @formore($items as $item, 3)
    <li>Item {{ $item }}</li>
    @more
    <li>+ {{ count($more) }} more</li>
    @formoreempty
    <li>No items</li>
    @endformore
</ul>

<h2>Empty</h2>
<ul>
    @formore($empty as $item, 3)
    <li>Item {{ $item }}</li>
    @more
    <li>+ {{ count($more) }} more</li>
    @formoreempty
    <li>No items</li>
    @endformore
</ul>
