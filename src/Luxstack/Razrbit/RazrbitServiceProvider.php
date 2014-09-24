<?php
use Illuminate\Support\ServiceProvider;
use Luxstack\Razrbit\Razrbit;

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
}