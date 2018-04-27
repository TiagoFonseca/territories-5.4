@extends('layouts.master')

@section('content')
	<h1>
		Maps
	</h1>
	<hr/>
<div class="row">
	@foreach ($maps as $map)		{{-- <article> --}}

		        <div class="col s12 m6 l4">
		          <div class="card">
		            <div class="card-image">
<!--                   Remember to replace map src below by map->picture -->
		              <img src="https://placeholdit.imgix.net/~text?txtsize=33&amp;txt=527%C3%97350&amp;w=527&amp;h=350">
		              <span class="card-title ">{{ $map->number }} - {{ $map->name }}</span>
		            </div>
		            <div class="card-content">
		              <p >I am a very simple card. </p>
		            </div>
		            <div class="card-action">
		              <a href= "{{ action('Frontend\FrontendMapsController@show', ($map->id)) }}" >View slips</a>
		              @if(Request::path()=== 'maps')
								<!--<div class="col-sm-3 col-md-3 ">-->
									<a href=" {{ action('Frontend\MapRequestController@store', ['map' => $map->id]) }}">Request map</a>
								<!--</div>-->
		        	@endif
		            </div>
		          </div>
		        </div>

			  <!--<div class="col-sm-6 col-md-6">-->
			  <!--  <div class="thumbnail">-->
			  <!--    <img src="https://placeholdit.imgix.net/~text?txtsize=33&txt=527%C3%97250&w=527&h=250" alt="...">-->
			  <!--    <div class="caption">-->
			  <!--      <div class="row ">-->
			  <!--      	<div class="col-sm-9 col-md-9"><a href= "{{ action('Frontend\FrontendMapsController@show', ($map->id)) }}"><h3>{{ $map->number }} - {{ $map->name }}</h3></a>-->

			  <!--      		<p>{{ $map->area }}	</p>-->
			  <!--      	</div>-->
			  <!--      	@if(Request::path()=== 'maps')-->
					<!--				<div class="col-sm-3 col-md-3 ">-->
					<!--					<a href=" {{ action('Frontend\MapRequestController@store', ['map' => $map->id]) }}" class="btn btn-primary pull-right" role="button">Request</a>-->
					<!--				</div>-->
			  <!--      	@endif-->
			  <!--      </div>-->

			  <!--    </div>-->
			  <!--  </div>-->
			  <!--</div>-->


		{{-- </article> --}}
	@endforeach
</div>
@stop
