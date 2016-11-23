<?php

namespace Panoscape\Remarks;

use Illuminate\Support\ServiceProvider;

class RemarksServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/migrations' => database_path('migrations')
        ], 'migrations');

        $this->app->singleton(Remarks::class, function($app){
            return new Remarks;
        });
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}