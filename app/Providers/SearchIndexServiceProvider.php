<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Repositories\SphinxIndexRepository;

class SearchIndexServiceProvider extends ServiceProvider
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
     * Bind App\Contracts\SearchIndexrepositoryInterface to the App\Repositories\SphinxIndexRepository class.
     *
     * @return App\Repositories\SphinxIndexRepository
     */
    public function register()
    {
        $this->app->bind('App\Contracts\SearchIndexRepositoryInterface', function() {
        	return new SphinxIndexRepository(env('SEARCH_INDEX_NAME'));
        });
    }
}
