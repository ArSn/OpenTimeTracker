<?php namespace App;


class Pause extends LocalizedModel
{
	public function getDates()
	{
		return ['start', 'end', 'created_at', 'updated_at'];
	}
}
