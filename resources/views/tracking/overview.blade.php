@extends('app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">Tracking controls</div>

				<div class="panel-body">

					<div class="btn-group btn-group-lg btn-group-justified" role="group">
						@if ($canStartDay)
						<a href="{{ route('tracking.day.start') }}" class="btn btn-success">Start day</a>
						@endif
						@if ($canStartPause)
						<a href="{{ route('tracking.pause.start') }}" class="btn btn-info">Start pause</a>
						@endif
						@if ($canStopPause)
						<a href="{{ route('tracking.pause.stop') }}" class="btn btn-warning">Stop pause</a>
						@endif
						@if ($canStopDay)
						<a href="{{ route('tracking.day.stop') }}" class="btn btn-danger">Stop day</a>
						@endif
					</div>

					<div class="btn-group btn-group-justified" role="group" style="margin-top: 15px;">
						<div class="btn-group btn-group-lg" role="group">
							<button class="btn btn-primary">Refresh overview</button>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">Tracking overview</div>

				<div class="panel-body">

					<table class="table table-bordered table-hover">
						<tr>
							<th>Date</th>
							<th>Start</th>
							<th>Pause start</th>
							<th>Pause end</th>
							<th>End</th>
							<th>Total working time</th>
							<th>Total pause</th>
							<th>Action</th>
						</tr>
						@foreach ($workdays as $workday)
							<tr class="success">
								<td>{{ showDate($workday->start) }}</td>
								<td>{{ showTime($workday->start) }}</td>
								<td>/</td>
								<td>/</td>
								<td>{{ showTime($workday->end) }}</td>
								<td>/</td>
								<td>/</td>
								<td>Edit</td>
							</tr>
						@endforeach
					</table>

				</div>
			</div>
		</div>
	</div>
</div>
@endsection
