<?php namespace App\Http\Controllers;

use App\Http\Requests;

use App\Pause;
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

	private function guardAgainstForbiddenPauseEditingAccess(Request $request)
	{
		$pauseIds = ($request->get('pause_starts') ?? []) + ($request->get('pause_ends') ?? []);

		foreach ($pauseIds as $pauseId => $irrelevantTime) {
			$pause = Pause::find($pauseId);
			if (empty($pause) || Gate::denies('edit-pause', $pause)) {
				abort(403, 'Forbidden.');
			}
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
	 * @param int|string $recordId
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function saveRecord(Request $request, $recordId)
	{
		$this->guardAgainstForbiddenRecordEditingAccess($recordId);
		$this->guardAgainstForbiddenPauseEditingAccess($request);

		// workday handling
		$workday = Workday::find($recordId);

		$workday->start = $workday->setTimeFromUserTimeZone($workday->start, $request->get('day_start'));
		$dayEnd = $request->get('day_end');
		if (empty($dayEnd) === false) {
			if (empty($workday->end)) {
				$workday->end = clone $workday->start;
			}
			$workday->end = $workday->setTimeFromUserTimeZone($workday->end, $dayEnd);
		}

		$workday->save();

		// pauses handling
		$pauseStarts = $request->get('pause_starts', []);
		$pauseEnds = $request->get('pause_ends', []);

		foreach ($pauseStarts as $id => $pauseStart) {
			$pauseEnd = $pauseEnds[$id];

			$pause = Pause::find($id);

			$pause->start = $pause->setTimeFromUserTimeZone($pause->start, $pauseStart);
			if (empty($pauseEnd) === false) {
				$pause->end = $pause->setTimeFromUserTimeZone($pause->end, $pauseEnd);
			}

			$pause->save();
		}

		return redirect()->route('tracking.overview');
	}
}
