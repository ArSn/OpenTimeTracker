<?php

namespace App\Routing;

class UrlGenerator extends \Illuminate\Routing\UrlGenerator
{
	protected function getScheme($secure)
	{
		if (config('app.force_https')) {
			return 'https://';
		}
		return parent::getScheme($secure);
	}
}