<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

/*
 * Information displaying actions.
 */
Route::get('/', 'AboutController@showAbout');
Route::get('imprint', 'ImprintController@showImprint');

/*
 * User home page, intended for a dashboard or something.
 */
Route::get('home', 'HomeController@index');

/*
 * Users settings page
 */
Route::get('user/settings', ['as' => 'user.settings', 'uses' => 'UserController@showSettings']);
Route::patch('user/settings', ['as' => 'user.settings.save', 'uses' => 'UserController@saveSettings']);

/*
 * Tracking handling itself (core functionality)
 */
Route::get('tracking', ['as' => 'tracking.overview', 'uses' => 'TrackingController@show']);
Route::get('tracking/day/start', ['as' => 'tracking.day.start', 'uses' => 'TrackingController@startDay']);
Route::get('tracking/day/stop', ['as' => 'tracking.day.stop', 'uses' => 'TrackingController@stopDay']);
Route::get('tracking/pause/start', ['as' => 'tracking.pause.start', 'uses' => 'TrackingController@startPause']);
Route::get('tracking/pause/stop', ['as' => 'tracking.pause.stop', 'uses' => 'TrackingController@stopPause']);
Route::get('tracking/edit/{id}', ['as' => 'tracking.record.edit', 'uses' => 'TrackingController@editRecord']);
Route::patch('tracking/edit/{id}', ['as' => 'tracking.record.save', 'uses' => 'TrackingController@saveRecord']);