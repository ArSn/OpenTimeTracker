<?php namespace App\Http\Controllers;

use App\Http\Requests;

class AboutController extends Controller
{
	/**
	 * Action to show the about page.
	 *
	 * @return \Illuminate\View\View
	 */
	public function showAbout()
	{
		return view('about');
	}
}
