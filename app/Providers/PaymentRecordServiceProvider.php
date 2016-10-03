<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class PaymentRecordServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Bind App\Contracts\PaymentRepositoryInterface to the App\Repositories\PaymentRecordRepository class.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Contracts\PaymentRecordRepositoryInterface', 'App\Repositories\PaymentRecordRepository');
    }
}
