<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Repositories\SodaApiRepository;
use App\Repositories\PaymentRecordRepository;

class RecordsApiServiceProvider extends ServiceProvider
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
     * Bind the RecordsApiRepositoryInterface to the App\Repositories\SodaApiRepository class.
     *
     * @return App\Repositories\SodaApiRepository
     */
    public function register()
    {
        $this->app->bind('App\Contracts\RecordsApiRepositoryInterface', function() {
        	return new SodaApiRepository(env('RECORDS_API_ENDPOINT'), env('RECORDS_API_DATASET'), new PaymentRecordRepository());
        });
    }
}
