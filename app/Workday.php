<?php namespace App;


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
}
