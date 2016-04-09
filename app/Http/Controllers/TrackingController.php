<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Workday;
use Illuminate\Http\Request;

use Gate;
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

		$user->workdays()->create(['start' => Carbon::now()]);

		$user->save();

		return redirect()->route('tracking.overview');
	}

	public function stopDay()
	{
		$user = $this->getUser();
		$workday = $user->todaysWorkday();

		$workday->fill(['end' => Carbon::now()]);
		$workday->save();

		return redirect()->route('tracking.overview');
	}

	public function startPause()
	{
		$user = $this->getUser();

		$user->todaysWorkday()->pauses()->create(['start' => Carbon::now()]);

		$user->save();

		return redirect()->route('tracking.overview');
	}

	public function stopPause()
	{
		$user = $this->getUser();
		$pause = $user->currentPause();

		$pause->fill(['end' => Carbon::now()]);
		$pause->save();

		return redirect()->route('tracking.overview');
	}

	private function guardAgainstForbiddenRecordEditingAccess($recordId)
	{
		$workday = Workday::find($recordId);

		if (empty($workday) || Gate::denies('edit-tracking', $workday)) {
			abort(403, 'Forbidden.');
		}
	}

	public function editRecord($recordId)
	{
		$this->guardAgainstForbiddenRecordEditingAccess($recordId);

		$workday = Workday::find($recordId);

		return view('tracking.edit', compact('workday'));
	}

	/**
	 * @param Request $request
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function saveRecord(Request $request, $recordId)
	{
		$this->guardAgainstForbiddenRecordEditingAccess($recordId);

		/** @var Workday $workday */
		$workday = Workday::find($recordId);

		$workday->start = date('Y-m-d', strtotime($workday->start)) . ' ' . $request->get('day_start');
		$workday->end = date('Y-m-d', strtotime($workday->end)) . ' ' . $request->get('day_end');

		$workday->save();

		// todo: add handling of pauses here

		return redirect()->route('tracking.overview');
	}
}
