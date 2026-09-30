<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Previously loaded the whole `users` table into every view (unused).
        // That ran a DB query on every page — including error pages — so it was removed.
    }
}
