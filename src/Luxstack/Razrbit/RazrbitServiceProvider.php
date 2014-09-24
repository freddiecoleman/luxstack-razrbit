<?php namespace Luxstack\Razrbit;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class RazrbitServiceProvider extends ServiceProvider {
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {
        $this->app->bind('Razrbit', function(){
            return new Razrbit;
        });
    }

    public function boot() {

        $loader = AliasLoader::getInstance();
        $loader->alias('Razrbit', 'Luxstack\Razrbit\Facades\Razrbit');

    }
}