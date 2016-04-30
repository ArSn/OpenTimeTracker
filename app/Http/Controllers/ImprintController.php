<?php namespace App\Http\Controllers;

use App\Http\Requests;

class ImprintController extends Controller
{
	/**
	 * Action to show the imprint from the configuration.
	 *
	 * @return \Illuminate\View\View
	 */
	public function showImprint()
	{
		$imprintFile = base_path() . DIRECTORY_SEPARATOR . config('app.imprint_file');
		$imprint = 'The administrator of this website has not yet configured an imprint to display here :(';
		if (file_exists($imprintFile)) {
			$imprint = file_get_contents($imprintFile);
		}
		return view('imprint', compact('imprint'));
	}
}
