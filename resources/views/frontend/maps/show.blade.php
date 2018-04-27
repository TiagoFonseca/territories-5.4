@extends('layouts.master')

@section('content')
	
		<h1>	{{$myMap['number']}} </h1>
		<div class="row">
			
			@foreach($myData as $key => $slip)
			
				<div class="col s11 m6 l6 ">
					<div class="card">

					<div class="card-content">
						<div class="card-title"><h5>Slip {{$key}}</h5></div>
						<div class="card-col-2">
							
							@if(isset($slip['street']))
            @foreach($slip['street'] as $key => $street)

               <div>{{$key}}</div>

									@foreach($street['house'] as $key => $house)

										<div class="ident-1">
											<input type="checkbox" id="house".{{$house['number']}}."-dis" disabled="disabled" />
													<label for="house"".{{$house['number']}}.""-dis">{{$house['number']}} {{$house['bellflat']}} {{$house['status']}} {{$house['type']}} {{$house['description']}}</label>
										</div>

									@endforeach

								@endforeach
							@endif
							
						</div>
					</div>
					</div>
				</div>

			@endforeach

	</div>
			<!-- Pagination controls -->
			{!! $myData->render() !!}
@stop
