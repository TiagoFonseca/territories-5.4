@extends('voyager::master')

@section('page_header')
    <div class="container-fluid">

          <h1 class="page-title">
              <i class=""></i> Assignments
            <a href="{{ url('admin/assignments/create') }}" class="btn btn-success btn-add-new">
                <i class="voyager-plus"></i> <span>Add new</span>
            </a>   
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
                    <th>Number</th><th>Publisher</th><th>Map</th><th>Assigned On</th><th>Finished On</th><th>Actions</th>
                </tr>
                      </thead>
                      <tbody>
                      <!--{{-- */$x=0;/* --}}-->
                      @foreach($assignments as $item)
                          <!--{{-- */$x++;/* --}}-->
                          <tr>
                              <td><a href="{{ url('admin/assignments', $item->id) }}">{{ $item->id }}</a></td>
                              <td><a href="{{ url('admin/users', $item->user->id) }}">{{ $item->user->name }}</a></td>
                              <td>{{ $item->map->number }} - {{ $item->map->name }}</td>
                              <td>{{ $item->assigned_on->diffForHumans() }}</td>
                              <td>{{ $item->finished_on }}</td>
                              <td>
                                  <a href="{{ url('admin/assignments/' . $item->id . '/edit') }}">
                                      <button type="submit" class="btn btn-primary btn-xs">Update</button>
                                  </a> /
                                  {!! Form::open([
                                      'method'=>'DELETE',
                                      'url' => ['admin/assignments', $item->id],
                                      'style' => 'display:inline'
                                  ]) !!}
                                      {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-xs']) !!}
                                  {!! Form::close() !!}
                              </td>
                          </tr>
                      @endforeach
                    </tbody>
                </table>
                <div class="pagination"> {!! $assignments->render() !!} </div>
            </div>
         </div>
      </div>
    </div>
  </div>
</div>
@endsection