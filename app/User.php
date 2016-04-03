<?php namespace App;

use Carbon\Carbon;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends LocalizedModel implements AuthenticatableContract, CanResetPasswordContract
{
	use Authenticatable, CanResetPassword;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['name', 'email', 'password', 'timezone'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token'];

	/**
	 * One-to-many relation for workdays of this user.
	 *
	 * @return \Eloquent
	 */
	public function workdays()
	{
		return $this->hasMany('App\Workday');
	}

	/**
	 * @return \Eloquent
	 */
	private function todaysResource()
	{

		$now = $this->currentDateTimeWithTimezone();
		$userTimezone = new \DateTimeZone($this->timezone);
		$appTimezone = new \DateTimeZone(\Config::get('app.timezone'));

		$midnightStart = $now->today($userTimezone);
		$midnightStart = Carbon::createFromFormat('Y-m-d H:i:s', $midnightStart, $userTimezone);
		$midnightStart->setTimezone($appTimezone);

		$midnightEnd = $now->tomorrow($userTimezone);
		$midnightEnd = Carbon::createFromFormat('Y-m-d H:i:s', $midnightEnd, $userTimezone);
		$midnightEnd->setTimezone($appTimezone);

		return $this->workdays()->whereBetween(
			'start',
			[$midnightStart->format('Y-m-d H:i:s'), $midnightEnd->format('Y-m-d H:i:s')]
		);
	}

	/**
	 * Each user can only have one workday per day
	 *
	 * @return bool
	 */
	public function canStartDay()
	{
		return $this->todaysResource()->count() == 0;
	}

	/**
	 * @return bool
	 */
	public function canEndDay()
	{
		// One can never end a day before starting it
		if ($this->canStartDay() || $this->hasCurrentPause()) {
			return false;
		}

		return $this->todaysResource()->whereNull('end')->count() == 1;
	}

	/**
	 * @return Workday|null
	 */
	public function todaysWorkday()
	{
		return $this->todaysResource()->first();
	}

	/**
	 * @return Pause|null
	 */
	public function currentPause()
	{
		$today = $this->todaysWorkday();
		if (empty($today)) {
			return null;
		}
		$pauses = $today->pauses();
		return $pauses->whereNotNull('start')->whereNull('end')->first();
	}

	/**
	 * @return bool
	 */
	private function hasCurrentPause()
	{
		$currentPause = $this->currentPause();
		return (empty($currentPause) === false || empty($currentPause->id) === false);
	}

	/**
	 * @return bool
	 */
	public function canStartPause()
	{
		$today = $this->todaysWorkday();
		if (empty($today) || $this->hasCurrentPause()) {
			return false;
		}

		// Default condition for first pause
		return $today->start != null && $today->end == null;
	}

	/**
	 * @return bool
	 */
	public function canEndPause()
	{
		if ($this->hasCurrentPause() === false || $this->canStartDay() || $this->canStartPause() || $this->canEndDay()) {
			return false;
		}
		return true;
	}

	/**
	 * @return Carbon
	 */
	public function currentDateTimeWithTimezone()
	{
		return Carbon::now($this->timezone);
	}
}
