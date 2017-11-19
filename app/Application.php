<?php

namespace App;

use App\Providers\AppServiceProvider;
use Illuminate\Contracts\Foundation\Application as ApplicationContract;
use Illuminate\Events\EventServiceProvider;
use Illuminate\Foundation\Application as BaseApp;
use Illuminate\Routing\RoutingServiceProvider;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class Application extends BaseApp implements ApplicationContract, HttpKernelInterface
{
	protected function registerBaseServiceProviders()
	{

		$this->register(new EventServiceProvider($this));

		$this->register(new AppServiceProvider($this));

		$this->register(new RoutingServiceProvider($this));
	}
}