<?php namespace App\Providers;

use App\Routing\UrlGenerator as AppUrlGenerator;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

	/**
	 * Register any application services.
	 *
	 * This service provider is a great spot to register your various container
	 * bindings with the application. As you can see, we are registering our
	 * "Registrar" implementation here. You can add your own bindings too!
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->bind(
			'Illuminate\Contracts\Auth\Registrar',
			'App\Services\Registrar'
		);

		$this->app->bind(UrlGenerator::class, AppUrlGenerator::class);
		$this->app->bind('url', AppUrlGenerator::class);
	}

}
