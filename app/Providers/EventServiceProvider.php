<?php

namespace App\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\SucursalNueva' => [
            'App\Listeners\CrearPreciosParaSucursalNueva',
        ],
        'App\Events\PostEmpleadoCreado' => [
            'App\Listeners\CrearUser',
        ],
        'App\Events\DatoContactoActualizado' => [
            'App\Listeners\ActualizarUser',
        ]
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);

        //
    }
}
