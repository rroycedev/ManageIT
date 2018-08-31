@extends('layouts.app')

@section('content')

@if ($errors->any())
    <div class="alert alert-danger" style="width: 80%;margin: auto;" id="alertwindow">
        <div style="float: left;">
          <ul style="margin-top: 0px;margin-bottom: 0px;padding-top: 0px;padding-bottom: 0px;">
               @foreach ($errors->all() as $error)
                 <li style="list-style: none;">{{ $error }}</li>
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

            <div class="card" style="width: 444px;">
                <div class="card-header">Add Database Connection Profile</div>
                <div class="card-body">
                {{ Form::open(array('url' => 'dbconnectionprofiles/insert')) }}
                    <div>
                        <label for="group">Group</label>
                        <select id="group" name="group" class="form-control ">
                                            <?php
foreach ($groups as $group) {
    ?>
                                                <option value="{{ $group->server_group_id }}">{{ $group->server_group_name }}</option>
                                            <?php
}
?>
                        </select>
                    </div>
                     <div style="margin-top: 20px;">
                        <label for="username">Username</label>
                        <input id="username" name="username" type="text" class="form-control" style="width: 300px;" value="" />
                    </div>
                    <div style="margin-top: 20px;">
                        <label for="password">Password</label>
                        <input id="password" name="password" type="password" class="form-control" style="width: 300px;" value="" />
                    </div>
                    <div style="margin-top: 20px;">
                        <label for="port">Port</label>
                        <input id="port" name="port" type="text" class="form-control" style="width: 100px;" value="" />&nbsp;(Default is 3306)
                    </div>
                       <div class="form-group" style="width: 145px;margin:auto;margin-top: 20px;">
                            <button id="addbtn" name="addbtn" type="submit" class="btn btn-primary">Add</button>
                            <button id="donebtn" name="donebtn" type="submit" class="btn btn-info">Done</button>
                        </div>
                        {{ Form::close() }}
                </div>
                <div class="card-footer">&nbsp;
                </div>
            </div>

    </div>
</div>
@endsection
