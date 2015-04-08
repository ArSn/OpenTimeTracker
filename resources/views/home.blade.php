@extends('app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">Home</div>

				<div class="panel-body">
					You are logged in!
					<?php
					//$date = new DateTime();
					//echo $date->format('Y-m-d H:i:s');

//					$date = \Carbon\Carbon::now();
//					$date->tz(new DateTimeZone('UTC'));
//					$officialDate = $date->toRfc2822String();
//					echo '<br >' . $officialDate;

					echo Auth::user()->created_at;

					?>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
