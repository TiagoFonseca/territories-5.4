@extends('layouts.master')

@section('content')
	<div id="prt-vue">
		@foreach($myData['slip'] as $key => $slip)
		<div class="col s12 m11 l11 offset-1">
			<h4 class="cyan-text"> 	{{$myMap['name']}} </h4>

			<slip-records :assslipid='{{$slip['assignment_slip_id']}}'></slip-records>

				<div class="card">
				<div class="card-content">
						<div class="card-title" data-slipID="{{$slip['id']}}"><h5>Slip	{{$key}}</h5></div>
				@foreach($slip['street'] as $key => $street)
				{!! Form::open(array('url' => '/changehousestatus', 'method'=>'POST')) !!}

					<div> {{$key}} </div>
					@foreach($street['house'] as $key => $house)
						<div class="ident-1">
							{{-- {!! Form::checkbox('completed', true, $house['assHouseStatus'], array('data-id' => $house['id'], 'data-map' => $myMap['id'], 'data-assignment' => $ass_id, 'class' => 'houseStatus filled-in', 'id' => 'house'.$house['id'], !$house['houseStatus'] ? '' : 'disabled')) !!}
							<label for="house{{$house['id']}}" class={{$house['houseStatus']}}>{{$house['number']}} {{$house['bellflat']}} {{$house['houseStatus']}} {{$house['type']}} {{$house['description']}}</label> --}}
							<label for="house{{$house['id']}}" class={{$house['houseStatus']}}>
								{!! Form::checkbox('completed', true, $house['assHouseStatus'], array('data-id' => $house['id'], 'data-map' => $myMap['id'], 'data-assignment' => $ass_id, 'class' => 'houseStatus filled-in', 'id' => 'house'.$house['id'], !$house['houseStatus'] ? '' : 'disabled')) !!}
								<span>{{$house['number']}} {{$house['bellflat']}} {{$house['houseStatus']}} {{$house['type']}} {{$house['description']}}</span>
							</label>

						</div>
					@endforeach

				@endforeach
				</div>

					</div>


				</div>

	@endforeach
	</div>

	</div>
	<!-- Ajax function to write on the database everytime the state of a checkbox is changed -->

	<script type="text/javascript">
	$(document).ready(function(){
	  $(".houseStatus").on("change", function() {
	    $.ajax({
	      url: '/changeHouseStatusShared',
	      type: "post",
	      data: {'status':$(this).is(':checked'), 'id':$(this).attr('data-id'), 'map_id': $(this).attr('data-map'), 'ass_id' : $(this).attr('data-assignment'), '_token': $('input[name=_token]').val()},
	      success: function(data){
	       // alert("saved successfully!");
	      }
	    });
	  });
	});
	</script>
@stop
