<?php namespace App\Http\Controllers;

use App\Http\Requests;

use Illuminate\Http\Request;

use Auth;
use App\User;

class UserController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return \App\Http\Controllers\UserController
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}

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
	 * Gets a list of all available timezones as array. The keys are the same as the values.
	 *
	 * @return array
	 */
	private function getTimeZoneList()
	{
		$zones = array();
		foreach (timezone_identifiers_list() as $zone) {
			$zones[$zone] = $zone;
		}
		return $zones;
	}

	/**
	 * Action to show the settings in an edit-form.
	 *
	 * @return \Illuminate\View\View
	 */
	public function showSettings()
	{
		$user = $this->getUser();
		$availableTimezones = $this->getTimeZoneList();

		return view('user.settings', compact('user', 'availableTimezones'));
	}

	/**
	 * Action to save the passed settings.
	 *
	 * @param Request $request
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function saveSettings(Request $request)
	{
		$user = $this->getUser();
		$user->fill($request->all());
		$user->save();

		return redirect()->route('user.settings');
	}
}
