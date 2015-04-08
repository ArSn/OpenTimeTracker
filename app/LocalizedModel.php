<?php namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
Use Auth;


/**
 * Extends the default eloquent model with automatic time zone setting for logged in users.
 *
 * @package App
 */
class LocalizedModel extends Model {

	/**
	 * Convert a DateTime to a storable string.
	 *
	 * @param  \DateTime|int  $value
	 * @return string
	 */
	public function fromDateTime($value)
	{
		$dateTime = $this->asDateTime($value);
		return parent::fromDateTime($dateTime);
	}

	/**
	 * Return a timestamp as DateTime object.
	 *
	 * @param  mixed  $value
	 * @return \Carbon\Carbon
	 */
	protected function asDateTime($value)
	{
		$dateTime = parent::asDateTime($value);
		$this->setTimeZoneIfAvailable($dateTime);
		return $dateTime;
	}

	private function setTimeZoneIfAvailable(Carbon $dateTime)
	{
		if (Auth::check() && Auth::user()->timezone) {
			$dateTime->setTimezone(Auth::user()->timezone);
		}
	}

}
