<?php

namespace Module\USERS;

use Illuminate\Support\ServiceProvider;

class USERServiceProvider extends ServiceProvider
{
	public function boot(){
		include __DIR__ . '/routes.php';
		//$this->loadviewsFrom(__DIR__ . '/view','bmi');
	}
	public function register(){
		$this->app['bmi'] = $this->app->share(function ($app) {
			return new BMI;
		});
	}
} 