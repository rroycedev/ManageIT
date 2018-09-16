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
                <div class="card-header">Add Server Threshold Profile</div>
                <div class="card-body">
                {{ Form::open(array('url' => 'serverthresholdprofiles/insert')) }}
                        <div>
                            <label for="profile_name">Name</label>
                            <input id="profile_name" name="profile_name" type="text" class="form-control" style="width: 400px;" value="" />
                       </div>
                       <div style="margin-top: 20px;">
                            <label for="description">Description</label>
                            <input id="description" name="description" type="text" class="form-control" style="width: 400px;" value="" />
                       </div>
                        <div style="margin-top: 20px;">
                        <table align="center">
    <tbody style="display: block;overflow-y:auto;max-height: 140px;padding:5px;border: 1px solid gray;">
@foreach ($param_names as $param)
    <tr>
        <td style="text-align: right;padding-right: 10px;">{{ $param->param_name }}</td>
        <td><input style="width: 50px;text-align: right;" id="param_value_{{$param->param_num }}" name="param_value_{{$param->param_num }}" type="text" value="" /></td>
        <td><label>({{ $param->param_label }})</label></td>
    </tr>
@endforeach
    </tbody>
</table>
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
