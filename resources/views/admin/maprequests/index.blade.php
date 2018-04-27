@extends('voyager::master')

@section('page_header')
    <div class="container-fluid">

          <h1 class="page-title">
              <i class=""></i> Map Requests
          </h1>
    </div>
@stop


@section('content')
<div class="page-content browse container-fluid">
  <div class="row">
    <div class="col-md-12">
       <div class="panel panel-bordered">
        <div class="panel-body">
            <!--<h1>Map Requests <a href="{{ url('admin/maprequests/create') }}" class="btn btn-primary pull-right btn-sm">Add New Map</a></h1>-->
            <div class="table-responsive">
               <table id="dataTable" class="table table-hover">
                    <thead>
                        <tr>
                            <th>User</th><th>Map</th><th>Status</th><th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    {{-- */$x=0;/* --}}
                    @foreach($maprequests as $item)
                        {{-- */$x++;/* --}}
                        <tr>
                            <td><a href="{{ url('admin/users', $item->user_id) }}">{{ $item->user->name }}</a></td><td><a href="{{ url('admin/maps', $item->map_id) }}">{{ $item->map->number }} - {{ $item->map->name }}</a></td><td>{{ $item->status }}</td>
                            <td>
                                <div>
                                   {!! Form::open(['url' => 'admin/map-requests', 'class' => 'form-horizontal']) !!}
                                   {!! Form::hidden('id', $item->id) !!}
                                   {!! Form::hidden('user_id', $item->user_id) !!}
                                   {!! Form::hidden('map_id', $item->map_id) !!}
                                   {!! Form::hidden('assigned_on', \Carbon\Carbon::now()) !!}
                                   {!! Form::hidden('status', 'Accepted') !!}    
                                   {!! Form::submit('Accept', ['class' => 'btn btn-sm btn-primary pull-right form-control']) !!}

                                  {!! Form::close() !!}


                                </div>
                                <div>
                                    {!! Form::open(['url' => 'admin/map-requests', 'class' => 'form-horizontal']) !!}
                                    {!! Form::hidden('id', $item->id) !!}
                                    {!! Form::hidden('user_id', $item->user_id) !!}
                                    {!! Form::hidden('map_id', $item->map_id) !!}

                                    {!! Form::hidden('status', 'Rejected') !!}    
                                    {!! Form::submit('Reject', ['class' => 'btn btn-sm btn-primary pull-right btn-info form-control']) !!}

                                   {!! Form::close() !!}
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="pagination"> {!! $maprequests->render() !!} </div>
            </div>
         </div>
      </div>
    </div>
  </div>
</div>
@endsection