<?php namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
Use Auth;


/**
 * Extends the default eloquent model with automatic time zone setting for logged in users.
 *
 * @package App
 */
class LocalizedModel extends Model
{
	/**
	 * @param string $key
	 * @return Carbon|mixed
	 */
	public function getAttributeValue($key)
	{
		if (in_array($key, $this->getDates())) {
			$value = $this->getAttributeFromArray($key);
			if ($value !== null) {
				$dateTime = $this->asDateTime($value);
				$this->setTimeZoneIfAvailable($dateTime);
				return $dateTime;
			}
		}
		return parent::getAttributeValue($key);
	}

	/**
	 * @param string $key
	 * @param mixed $value
	 * @return $this
	 */
	public function setAttribute($key, $value)
	{
		if ($value && in_array($key, $this->getDates())) {
			if (Auth::check() && Auth::user()->timezone) {
				$value = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $value, new \DateTimeZone(Auth::user()->timezone));
			}
			$value = $this->asDateTime($value);
			$value->setTimezone(new \DateTimeZone(\Config::get('app.timezone')));
		}
		return parent::setAttribute($key, $value);
	}

	/**
	 * Sets the timezone of the user to the passed Carbon DateTime object, IFF it is available.
	 *
	 * @param Carbon $dateTime
	 */
	private function setTimeZoneIfAvailable(Carbon $dateTime)
	{
		if (Auth::check() && Auth::user()->timezone) {
			$dateTime->setTimezone(Auth::user()->timezone);
		}
	}

}
