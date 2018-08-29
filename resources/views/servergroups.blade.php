@extends('layouts.app')

@section('content')
@if ($errors->any())
    <div class="alert alert-danger" style="width: 80%;margin: auto;" id="alertwindow">
        <div style="float: left;">
          <ul style="margin-top: 0px;margin-bottom: 0px;padding-top: 0px;padding-bottom: 0px;">
               @foreach ($errors->all() as $error)
                 <li>{{ $error }}</li>
             @endforeach
            </ul>
        </div>
        <div style="float: right;"><i class="fa fa-times" aria-hidden="true" style="cursor: pointer;color: white;" onclick="$('#alertwindow').hide()"></i></div>
        <div style="clear: both;"></div>
    </div>
@endif
@if(session()->has('message'))
    <div class="alert alert-success" style="width: 80%;margin: auto;" id="alertwindow">
        <div style="float: left;">{{ session()->get('message') }}</div>
        <div style="float: right;"><i class="fa fa-times" aria-hidden="true" style="cursor: pointer;color: white;" onclick="$('#alertwindow').hide()"></i></div>
        <div style="clear: both;"></div>
    </div>
@endif
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Server Groups</div>

                <div class="card-body">

                    <table id="example" class="table table-striped table-bordered"  style="width:100%">
                        <thead>
                            <tr>
                                <th>Server Group</th>
                                <th>Description</th>
                                <th>&nbsp;</th>
                                <th>&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($servergroups as $servergroup)
                        <tr>
                            <td>{{ $servergroup->server_group_name }}</td>
                            <td>{{ $servergroup->description }}</td>
                            <td style="width: 80px;"><a class="btn btn-primary" href="{{ url("servergroups/change/" . $servergroup->server_group_id) }}" >Change</a></td>
                            <td style="width: 80px;"><a class="btn btn-primary" href="{{ url("servergroups/delete/" . $servergroup->server_group_id) }}" >Delete</a></td>
                        </tr>
                        @endforeach

                        </tbody>
                    </table>

                </div>
                <div class="card-footer">
                    <div style="width: 110px;margin: auto;"><a class="btn btn-primary" href="{{ url("servergroups/add") }}" >Add</a></div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Delete Server Group</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Are you sure you wish to delete this server group?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Delete</button>
      </div>
    </div>
  </div>
</div>

@endsection
