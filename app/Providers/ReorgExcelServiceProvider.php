<?php

namespace App\Providers;

use App\Repositories\ExcelRepository;

use Illuminate\Support\ServiceProvider;

use App\Contracts\ExcelRepositoryInterface;

class ReorgExcelServiceProvider extends ServiceProvider
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
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Contracts\ExcelRepositoryInterface', 'App\Repositories\ExcelRepository');
    }
}
