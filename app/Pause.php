<?php namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Pause extends Model
{
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

	/**
	 * @todo extract into trait
	 * @return mixed
	 */
	public function getStartTimeAttribute()
	{
		return date('H:i:s', strtotime($this->start));
	}

	/**
	 * @todo extract into trait
	 * @return mixed
	 */
	public function getEndTimeAttribute()
	{
		return date('H:i:s', strtotime($this->end));
	}
}
