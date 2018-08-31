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
                       <div style="margin: auto;margin-top: 20px;width: 90px;">
                            <label for="profile_type">Type</label>
                            <select id="profile_type" name="profile_type" class="form-control" style="width: 90px;" onchange="onThresholdProfileTypeChange()">
                                    <option value="CPU">CPU</option>
                                    <option value="Memory">Memory</option>
                            </select>
                        </div>
                        <div style="margin-top: 20px;">
                            <table align="center">
                                <tr>
                                    <td>
                                        <label for="warning_level">Warning Level</label>
                                        <div>
                                            <input id="warning_level" name="warning_level" type="text" class="form-control" style="width: 80px;float: left;text-align: right;" value="" />
                                            <label id="warningleveltype" style="float: left;line-height: 36px;"></label>
                                            <div style="clear: both;"></div>
                                        </div>
                                    </td>
                                    <td style="padding-left: 30px;">
                                        <label for="error_level">Error Level</label>
                                        <div>
                                            <input id="error_level" name="error_level" type="text" class="form-control" style="width: 80px;float: left;text-align: right;" value="" />
                                            <label id="errorleveltype" style="float: left;line-height: 36px;"></label>
                                            <div style="clear: both;"></div>
                                        </div>
                                    </td>
                                </tr>
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
