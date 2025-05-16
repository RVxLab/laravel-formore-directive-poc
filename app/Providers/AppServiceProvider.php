<?php

namespace App\Providers;

use App\Blade\ForMoreDirective;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Blade::directive('formore', fn (string $expression) => ForMoreDirective::forMore($expression));
        Blade::directive('more', fn () => ForMoreDirective::more());
        Blade::directive('formoreempty', fn (?string $expression) => ForMoreDirective::empty($expression));
        Blade::directive('endformore', fn () => ForMoreDirective::endForMore());
    }
}
