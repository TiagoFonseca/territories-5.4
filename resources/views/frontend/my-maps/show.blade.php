@extends('layouts.master')

@section('content')
	<h2> 	{{$myMap['name']}} </h2	>
		{!! $myData->render() !!}

	<div class="row" id="prt-vue">
		@foreach($myData as $key => $slip)
			<div class="col s12 m6 l6 ">

			{{-- @include('partials.publisherRecords') --}}
			<slip-records :assslipid='{{$slip['assignment_slip_id']}}'></slip-records>

				<div class="card">

					<div class="card-content">


						<div class="card-title" data-slipID = "{{$slip['id']}}"><h5>Slip	{{$key}}</h5></div>
						{!! Form::open(array('url' => '/changeHouseStatus', 'method'=>'POST')) !!}

						@foreach($slip['street'] as $key => $street)

					<div> {{$key}} </div>
					@foreach($street['house'] as $key => $house)
						{{-- <div class="ident-1">
							{!! Form::checkbox('completed', true, $house['assHouseStatus'], array('data-id' => $house['id'], 'data-map' => $myMap['id'], 'data-assignment' => $house['ass_id'], 'class' => 'houseStatus filled-in ', 'id' => 'house'.$house['id'], !$house['houseStatus'] ? '' : 'disabled')) !!}
							<label for="house{{$house['id']}}" class={{$house['houseStatus']}}>{{$house['number']}} {{$house['bellflat']}} {{$house['houseStatus']}} {{$house['type']}} {{$house['description']}}</label>
						</div> --}}
						<div class="ident-1">
							<label for="house{{$house['id']}}">
									{!! Form::checkbox('completed', true, $house['assHouseStatus'], array('data-id' => $house['id'], 'data-map' => $myMap['id'], 'data-assignment' => $house['ass_id'], 'class' => 'houseStatus filled-in ', 'id' => 'house'.$house['id'], !$house['houseStatus'] ? '' : 'disabled')) !!}
									<span>{{$house['number']}} {{$house['bellflat']}} {{$house['houseStatus']}} {{$house['type']}} {{$house['description']}}</span>
							</label>
						</div>

					@endforeach

				@endforeach
				{!! Form::close() !!}

					<br/>
					<div class="card-action" data-slipID="{{$slip['id']}}" data-assignmentID="{{$house['ass_id']}}">
		              <!-- Share button
		              		Sets Share to 1 for the current slip -->

						{{-- {!! Form::checkbox('shared', true, $slip['shared'], array('class' => 'share', 'id' => $slip['id'])) !!}
	    		  <label for="{{$slip['id']}}">Share slip</label>
						{!! Form::close() !!} --}}
								<label for="{{$slip['id']}}">
									{!! Form::checkbox('shared', true, $slip['shared'], array('class' => 'share', 'id' => $slip['id'])) !!}
										<span>Share slip</span>
									{!! Form::close() !!}
								</label>

		            </div>

							{{-- <div id="shared-{{$slip['id']}}" class="shared-URL" style="display:none">URL: {!! url('/assignments/'.$house['ass_id_encrypted'].'/slips/'.$slip['id_encrypted'].''); !!}</div>
							<a data-text="{!! url('/assignments/'.$house['ass_id_encrypted'].'/slips/'.$slip['id_encrypted'].''); !!}"  class="whatsapp btn">Share</a> --}}

							<div id="shared-{{$slip['id']}}" class="shared-URL" style="display:none">URL: {!! $house['autologin_url'] !!}</div>
							<a data-text="{!! $house['autologin_url'] !!}"  class="whatsapp btn">Share</a>
					</div>


				</div>


			</div>
	@endforeach



	</div>


	<!-- Ajax function to write on the database everytime the state of a checkbox is changed -->



@stop
