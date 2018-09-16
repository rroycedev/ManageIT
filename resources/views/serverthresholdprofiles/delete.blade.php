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

            <div class="card" style="width: 1000px;">
                <div class="card-header">Delete Server Threshold Profile</div>

                <div class="card-body">
                {{ Form::open(array('url' => 'serverthresholdprofiles/remove')) }}
                    <input type="hidden" id="server_threshold_profile_id" name="server_threshold_profile_id" value="{{ $profile->server_threshold_profile_id }}" />
                        <div class="form-group">
                            <h6 style="text-align: center;">Are you sure you wish to delete server threshold profile "{{$profile->profile_name}}"?</h6>
                       </div>
                        <div class="form-group" style="width: 155px;margin:auto;margin-top: 20px;">
                            <button id="cancelbtn" name="cancelbtn" type="submit" class="btn btn-primary">Cancel</button>
                            <button id="deletebtn" name="deletebtn" type="submit" class="btn btn-primary">Delete</button>
                        </div>
                        {{ Form::close() }}
                </div>
                <div class="card-footer">&nbsp;
                </div>
            </div>
        </div>
</div>
@endsection
