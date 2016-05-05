<?php namespace App;

use Auth;
use Carbon\Carbon;
use Config;

/**
 * This trait is supposed to simplify converting user imput times and date times to the applications timezone.
 */
trait CanInputUserTimeZone
{
	function setTimeFromUserTimeZone(Carbon $carbon, $time)
	{
		$user = Auth::user();

		$carbon->setTimezone($user->timezone);
		$carbon->setTimeFromTimeString($time);
		$carbon->setTimezone(Config::get('app.timezone'));

		return $carbon;
	}
}