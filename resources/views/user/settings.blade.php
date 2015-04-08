@extends('app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">Your Settings</div>

				<div class="panel-body">

					{!! Form::model($user, ['method' => 'PATCH', 'class' => 'form-horizontal', 'role' => 'form']) !!}

					<div class="form-group">
						{!! Form::label('Name', null, ['class' => 'col-md-4 control-label']) !!}
						<div class="col-md-6">
							{!! Form::text('name', null, ['class' => 'form-control']) !!}
						</div>
					</div>

					<div class="form-group">
						{!! Form::label('Time zone', null, ['class' => 'col-md-4 control-label']) !!}
						<div class="col-md-6">
							{!! Form::select('timezone', $availableTimezones, $user->timezone, ['class' => 'form-control']) !!}
						</div>
					</div>

					<div class="form-group">
						<div class="col-md-6 col-md-offset-4">
							{!! Form::button('Save changes', ['class' => 'btn btn-primary', 'type' => 'submit']) !!}
						</div>
					</div>

					{!! Form::close() !!}

				</div>
			</div>
		</div>
	</div>
</div>
@endsection
