<?php namespace App;

use Carbon\Carbon;

class Workday extends LocalizedModel
{
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
			$end->setTimezone(new \DateTimeZone(\Config::get('app.timezone')));
			$end = strtotime($end);
		}

		/** @var Carbon $start */
		$start = $this->start;
		$start->setTimezone(new \DateTimeZone(\Config::get('app.timezone')));

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
}
