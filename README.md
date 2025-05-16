# `@formore` Blade Directive PoC

A proof of concept on how a `@formore` directive could work and what it could look like.

## How does it work?

The `@formore` directive works very similarly to a `@forelse` directive, with the exception that it requires 2 parameters in its expression:

- The `foreach` definition `$thingToIterate as $itemInIterable`
- The limit

The actual workings is reverse engineered from the `@forelse` loop.

First, a loop iteration is created with a data source, collecting the iterable into a collection for consistency. This collection is then sliced twice, once to limit the amount of items defined in the Blade expression, another for the remaining items.

Then the actual loop is started. If it's able to iterate at least once, we know the loop isn't empty, and we mark it as such.

After the loop has ended, the `@more` check comes into play. It checks if there are any more items available. If so, they're made available in the `$more` variable. `$more` is a `Collection<int, T>`.

If there are no more items, the `@more` directive is skipped.

If the initial iterable is empty, the `@formore` directive works identical to the `@forelse` directive, except that the `@empty` directive is called `@formoreempty` to avoid collisions.

Finally, `@endformore` ties everything up. It's possible to use `@endforelse` as well, as they both resolve to `<?php endif; ?>`, but that doesn't really make sense from a semantics point of view.

This PoC doesn't support nested `@formore` directives.

### References

- [`@forelse`](https://github.com/illuminate/view/blob/90475bd37a40adee2182a744c867a0579f96ee50/Compilers/Concerns/CompilesLoops.php#L24)
- [`@empty`](https://github.com/illuminate/view/blob/90475bd37a40adee2182a744c867a0579f96ee50/Compilers/Concerns/CompilesLoops.php#L51)
- [`@endforelse`](https://github.com/illuminate/view/blob/90475bd37a40adee2182a744c867a0579f96ee50/Compilers/Concerns/CompilesLoops.php#L67)

## Example

```php
$items = [1, 2, 3, 4, 5];
````

```bladehtml
<ul>
    @formore($items as $item, 3)
    <li>Item {{ $item }}</li>
    @more
    <li>+ {{ count($more) }} more</li>
    @formoreempty
    <li>No items</li>
    @endformore
</ul>
```
