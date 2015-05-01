<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Auth;
use App\User;
use Carbon\Carbon;

class TrackingController extends Controller
{
	/**
	 * Gets the currently signed in user.
	 *
	 * @return User
	 */
	private function getUser()
	{
		return Auth::user();
	}

	/**
	 * Shows an overview of the tracking for the curent month.
	 */
	public function show()
	{
		$user = $this->getUser();

		$workdays = $user->workdays;

		$canStartDay = $user->canStartDay();
		$canStopDay = $user->canEndDay();

		$canStartPause = $user->canStartPause();
		$canStopPause = $user->canEndPause();

		$now = Carbon::now();
		$now->setTimezone($user->timezone);

		return view('tracking.overview', compact(
			'workdays',
			'canStartDay',
			'canStopDay',
			'canStartPause',
			'canStopPause',
			'now'
		));
	}

	public function startDay()
	{
		$user = $this->getUser();

		$now = Carbon::now();
		$user->workdays()->create(['start' => $now]);

		$user->save();

		return redirect()->route('tracking.overview');
	}

	public function stopDay()
	{
		$user = $this->getUser();
		$workday = $user->todaysWorkday();

		$now = Carbon::now();
		$workday->fill(['end' => $now]);
		$workday->save();

		return redirect()->route('tracking.overview');
	}

	public function startPause()
	{
		$user = $this->getUser();

		$now = Carbon::now();
		$user->todaysWorkday()->pauses()->create(['start' => $now]);

		$user->save();

		return redirect()->route('tracking.overview');
	}

	public function stopPause()
	{
		$user = $this->getUser();
		$pause = $user->currentPause();

		$now = Carbon::now();
		$pause->fill(['end' => $now]);
		$pause->save();

		return redirect()->route('tracking.overview');
	}

}
