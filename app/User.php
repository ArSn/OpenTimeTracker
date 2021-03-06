<?php namespace App;

use Carbon\Carbon;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;

/**
 * App\User
 *
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $timezone
 * @property string $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Workday[] $workdays
 * @method static \Illuminate\Database\Query\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereTimezone($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class User extends Model implements AuthenticatableContract, CanResetPasswordContract
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

		return $this->workdays()->whereBetween(
			'start',
			[$now->format('Y-m-d 00:00:00'), $now->format('Y-m-d 23:59:59')]
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
}
