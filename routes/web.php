<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $items = [1, 2, 3, 4, 5];
    $empty = [];

    return view('poc', [
        'items' => $items,
        'empty' => $empty,
    ]);
});
