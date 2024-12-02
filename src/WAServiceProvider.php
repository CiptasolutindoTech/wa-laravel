<?php

namespace Cst\WALaravel;

use Illuminate\Support\ServiceProvider;

class WAServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/wa.php' => config_path('wa.php'),
        ],['cst','wa','wa-config']);
    }
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/wa.php',
            'wa'
        );
    }
}
