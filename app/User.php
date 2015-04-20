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
		$now = Carbon::now();
		return $this->workdays()->where('start', 'like', $now->format('Y-m-d%'));
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
		if ($this->canStartDay()) {
			return false;
		}

		return $this->todaysResource()->where('end', null)->count() == 1;
	}

	/**
	 * @return Workday
	 */
	public function todaysWorkday()
	{
		return $this->todaysResource()->first();
	}

	/**
	 * @return bool
	 */
	public function canStartPause()
	{
		$today = $this->todaysWorkday();
		if (empty($today)) {
			return false;
		}

		// TODO: Check if there is no other pause going on at the moment

		// Default condition for first pause
		return $today->start != null && $today->end == null;
	}

	/**
	 * @return bool
	 */
	public function canEndPause()
	{
		$today = $this->todaysWorkday();
		if (empty($today)) {
			return false;
		}

//		$pauses = ;

		foreach ($today->pauses() as $pause) {
			dd($pause);
		}
	}
}
