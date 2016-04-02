@extends('app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Edit tracking for day {{ $workday->date }}</div>

                    <div class="panel-body">

                        {!! Form::model($workday, ['method' => 'PATCH', 'class' => 'form-horizontal', 'role' => 'form']) !!}

                        <div class="form-group">
                            {!! Form::label('Day Start', null, ['class' => 'col-md-4 control-label']) !!}
                            <div class="col-md-6">
                                {!! Form::time('day_start', $workday->startTime, ['class' => 'form-control']) !!}
                            </div>
                        </div>


                        <div class="form-group">
                            {!! Form::label('Pauses', null, ['class' => 'col-md-4 control-label']) !!}
                            <div class="col-md-6">
                                <table class="table table-hover" id="pauses">
                                    <tr>
                                        <th>Start</th>
                                        <th>End</th>
                                    </tr>
                                    @foreach ($workday->pauses as $pause)
                                        <tr>
                                            <td>{!! Form::time('pause_starts[' . $pause->id . ']', $pause->startTime, ['class' => 'form-control']) !!}</td>
                                            <td>{!! Form::time('pause_ends[' . $pause->id . ']', $pause->endTime, ['class' => 'form-control']) !!}</td>
                                        </tr>
                                    @endforeach
                                    {{--<tr>--}}
                                        {{--<td>{!! Form::time('pause_starts[]', null, ['class' => 'form-control']) !!}</td>--}}
                                        {{--<td>{!! Form::time('pause_ends[]', null, ['class' => 'form-control']) !!}</td>--}}
                                    {{--</tr>--}}
                                </table>
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('Day End', null, ['class' => 'col-md-4 control-label']) !!}
                            <div class="col-md-6">
                                {!! Form::time('day_end', $workday->endTime, ['class' => 'form-control']) !!}
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
