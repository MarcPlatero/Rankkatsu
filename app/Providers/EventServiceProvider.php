<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        // Pots deixar-ho buit, no cal afegir res per ara
    ];

    public function boot(): void
    {
        //
    }
}
