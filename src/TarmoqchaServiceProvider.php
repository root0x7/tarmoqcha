<?php 

namespace Root0x7\Tarmoqcha;


use Illuminate\Support\ServiceProvider;

class TarmoqchaServiceProvider extends ServiceProvider{
	public function register(){
		$this->mergeConfigFrom(__DIR__.'/../config/tarmoqcha.php','tarmoqcha');

		$this->app->singleton('tarmoqcha',function($app){
			$this->publishes([
				__DIR__.'/../config/tarmoqcha.php' => config_path('tarmoqcha.php'),
			], 'config');

			$this->commands([
				Commands\TunnelStartCommand::class,
				Commands\TunnelStopCommand::class,
				Commands\TunnelStatusCommand::class,
			]);
		});
	}
}