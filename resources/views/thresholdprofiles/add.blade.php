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
                <div class="card-header">Add Threshold Profile</div>

                <div class="card-body">
                {{ Form::open(array('url' => 'thresholdprofiles/insert')) }}
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input id="name" name="name" type="text" class="form-control" value="" />
                       </div>
                       <div class="form-group">
                            <label for="name">Description</label>
                            <input id="description" name="description" type="text" class="form-control" value="" />
                       </div>
                        <div class="form-group">
                            <label for="type">Type</label>
                            <select id="type" name="type" class="form-control ">
                                    <option value="CPU">CPU</option>
                                    <option value="Memory">Memory</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="warninglevel">Warning Level</label>
                            <input id="warninglevel" name="warninglevel" type="text" class="form-control" value="" />
                       </div>
                       <div class="form-group">
                            <label for="errorlevel">Error Level</label>
                            <input id="errorlevel" name="errorlevel" type="text" class="form-control" value="" />
                       </div>
                       <div class="form-group" style="width: 55px;margin:auto;margin-top: 20px;">
                            <button type="submit" class="btn btn-primary">Add</button>
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
