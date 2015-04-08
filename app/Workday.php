<?php namespace App;


class Workday extends LocalizedModel
{
	public function getDates()
	{
		return ['start', 'end', 'created_at', 'updated_at'];
	}
}
