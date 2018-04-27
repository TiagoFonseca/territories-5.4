@extends('voyager::master');

@section('page_title', __('voyager.generic.viewing').' '.$dataType->display_name_plural)

@section('main-content')

    <!--<h1>Map Requests <a href="{{ url('admin/maprequests/create') }}" class="btn btn-primary pull-right btn-sm">Add New Map</a></h1>-->
    <div class="table">
        <table class="table table-bordered table-striped table-hover">
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
                           {!! Form::submit('Accept', ['class' => 'btn btn-primary form-control']) !!}
                        
                           {!! Form::close() !!}

                        
                        </div>
                        <div>
                            {!! Form::open(['url' => 'admin/map-requests', 'class' => 'form-horizontal']) !!}
                            {!! Form::hidden('id', $item->id) !!}
                            {!! Form::hidden('user_id', $item->user_id) !!}
                            {!! Form::hidden('map_id', $item->map_id) !!}

                            {!! Form::hidden('status', 'Rejected') !!}    
                            {!! Form::submit('Reject', ['class' => 'btn btn-info form-control']) !!}
                        
                           {!! Form::close() !!}
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="pagination"> {!! $maprequests->render() !!} </div>
    </div>

@endsection