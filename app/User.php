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
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function workdays()
	{
		return $this->hasMany('App\Workday');
	}

	/**
	 * @return Workday
	 */
	public function todaysWorkday()
	{
		$now = Carbon::now();
		return $this->workdays()->where('start', 'like', $now->format('Y-m-d%'))->first();
	}

	/**
	 * Each user can only have one workday per day
	 *
	 * @return bool
	 */
	public function canStartDay()
	{
		$now = Carbon::now();
		return $this->workdays()->where('start', 'like', $now->format('Y-m-d%'))->count() == 0;
	}

	public function canEndDay()
	{
		// One can never end a day before starting it
		if ($this->canStartDay()) {
			return false;
		}

		$now = Carbon::now();
		return $this->workdays()->where('start', 'like', $now->format('Y-m-d%'))->where('end', null)->count() == 1;
	}
}
