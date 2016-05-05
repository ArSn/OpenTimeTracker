<?php namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Pause
 *
 * @property integer $id
 * @property integer $workday_id
 * @property \Carbon\Carbon $start
 * @property \Carbon\Carbon $end
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Workday $workday
 * @method static \Illuminate\Database\Query\Builder|\App\Pause whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Pause whereWorkdayId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Pause whereStart($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Pause whereEnd($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Pause whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Pause whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Pause extends Model
{
	use CanInputUserTimeZone;

	protected $fillable = ['start', 'end'];

	public function getDates()
	{
		return ['start', 'end', 'created_at', 'updated_at'];
	}

	/**
	 * Many-to-one relation for Workday->Pauses.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function workday()
	{
		return $this->belongsTo('App\Workday');
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
}
