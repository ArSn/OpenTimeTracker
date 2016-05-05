<?php

namespace App\Providers;

use App\Pause;
use App\User;
use App\Workday;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
	/**
	 * Register any application authentication / authorization services.
	 *
	 * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
	 * @return void
	 */
	public function boot(GateContract $gate)
	{
		$this->registerPolicies($gate);

		$gate->define('edit-tracking', function ($user, $workday) {
			/** @var User $user */
			/** @var Workday $workday */
			return $user->id === $workday->user_id;
		});

		$gate->define('edit-pause', function ($user, $pause) {
			/** @var User $user */
			/** @var Pause $pause */
			return $user->id === $pause->workday->user_id;
		});
	}
}