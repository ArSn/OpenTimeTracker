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

		return view('tracking.overview', compact(
			'workdays',
			'canStartDay',
			'canStopDay'
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

	}

	public function stopPause()
	{

	}

}
