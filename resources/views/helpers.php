<?php

/**
 * This file is supposed to contain all the custom view helpers.
 */

use Carbon\Carbon;

/**
 * Returns only the date part of the passed date time object, as a formatted string.
 *
 * @param Carbon|null $dateTime
 * @return string
 */
function showDate(Carbon $dateTime = null)
{
	if ($dateTime == null) {
		return '';
	}
	return $dateTime->format('Y-m-d');
}

/**
 * Returns only the time part of the passed date time object, as a formatted string.
 *
 * @param Carbon|null $dateTime
 * @return string
 */
function showTime(Carbon $dateTime = null)
{
	if ($dateTime == null) {
		return '';
	}
	return $dateTime->format('H:i:s');
}

/**
 * Returns a formatted duration string in HH:MM:SS format for passed seconds.
 *
 * @param int $duration Duration in seconds.
 * @return string
 */
function showTimeFromDuration($duration)
{
	$hours = floor($duration / 3600);
	$minutes = floor(($duration / 60) % 60);
	$seconds = $duration % 60;

	return sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);
}