<?php namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Workday
 *
 * @property integer $id
 * @property integer $user_id
 * @property \Carbon\Carbon $start
 * @property \Carbon\Carbon $end
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Pause[] $pauses
 * @property-read mixed $date
 * @method static \Illuminate\Database\Query\Builder|\App\Workday whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Workday whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Workday whereStart($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Workday whereEnd($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Workday whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Workday whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Workday extends Model
{
	use CanInputUserTimeZone;

	protected $fillable = ['start', 'end'];

	public function getDates()
	{
		return ['start', 'end', 'created_at', 'updated_at'];
	}

	/**
	 * Many-to-one relation for User->Workdays.
	 *
	 * @return \Eloquent
	 */
	public function user()
	{
		return $this->belongsTo('App\User');
	}

	/**
	 * One-to-many relation for pauses of this workday.
	 *
	 * @return \Eloquent
	 */
	public function pauses()
	{
		return $this->hasMany('App\Pause');
	}

	/**
	 * @todo extract into trait
	 * @return int
	 */
	public function duration()
	{
		if (empty($this->start)) {
			return 0;
		}

		$end = $this->end;
		if (empty($end)) {
			$end = time();
		} else {
			$end = strtotime($end);
		}

		/** @var Carbon $start */
		$start = $this->start;

		return ($end - strtotime($start));
	}

	public function pausesDuration()
	{
		$duration = 0;
		/** @var Pause $pause */
		foreach ($this->pauses as $pause) {
			$duration += $pause->duration();
		}
		return $duration;
	}

	public function workDuration()
	{
		return ($this->duration() - $this->pausesDuration());
	}

	public function getDateAttribute()
	{
		return date('Y-m-d', strtotime($this->start));
	}
}
