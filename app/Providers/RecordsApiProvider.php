<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Repositories\SodaApiRepository;
use App\Repositories\PaymentRecordRepository;

class RecordsApiProvider extends ServiceProvider
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
        $this->app->bind('App\Contracts\RecordsApiRepositoryInterface', function() {
        	return new SodaApiRepository(env('RECORDS_API_ENDPOINT'), env('RECORDS_API_DATASET'), new PaymentRecordRepository());
        });
    }
}
