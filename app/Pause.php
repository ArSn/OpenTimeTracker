<?php namespace App;


class Pause extends LocalizedModel
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
}
