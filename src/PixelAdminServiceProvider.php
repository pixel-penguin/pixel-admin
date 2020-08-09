<?php

namespace PixelPenguin\Admin;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;
use PixelPenguin\Admin\Facade;

class PixelAdminServiceProvider extends ServiceProvider{
	
	public function boot(){
		
		$this->loadRoutesFrom(__DIR__.'/routes/admin.php');
		$this->loadRoutesFrom(__DIR__.'/routes/json.php');
		
		$this->loadViewsFrom(__DIR__.'/views' , 'pixel-admin');
				
		$this->loadMigrationsFrom(__DIR__.'/database/migrations');
		
		
		$this->app['router']->aliasMiddleware('checkifmainadmin', \PixelPenguin\Admin\Middleware\CheckIfMainAdmin::class);
		
	}
	
	
	public function register(){
		$this->app->booting(function() {
            $loader = AliasLoader::getInstance();
            $loader->alias('PixelPenguinAdmin', Facade::class);
        });
	}
}