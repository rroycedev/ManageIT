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
        <div class="col-md-6">
            <div class="card" style="width: 18rem">
                <div class="card-header">Change Server Group</div>

                <div class="card-body">
                {{ Form::open(array('url' => 'servergroups/update')) }}
                        <input type="hidden" id="server_group_id" name="server_group_id" value="{{ $servergroup->server_group_id }}" />
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input id="name" name="name" type="text" class="form-control" value="{{ $servergroup->server_group_name }}" />
                       </div>
                       <div class="form-group">
                            <label for="name">Description</label>
                            <input id="description" name="description" type="text" class="form-control" value="{{ $servergroup->description }}" />
                       </div>
                        <div class="form-group" style="width: 55px;margin:auto;margin-top: 20px;">
                            <button type="submit" class="btn btn-primary">Change</button>
                        </div>
                        {{ Form::close() }}
                </div>
                <div class="card-footer">&nbsp;
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
