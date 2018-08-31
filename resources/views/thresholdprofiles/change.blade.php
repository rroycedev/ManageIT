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
                <div class="card-header">Change Threshold Profile</div>
                <div class="card-body">
                {{ Form::open(array('url' => 'thresholdprofiles/update')) }}
                    <input type="hidden" id="profile_id" name="profile_id" value="{{ $profile->profile_id }}" />
                        <div>
                            <label for="name">Name</label>
                            <input id="name" name="name" type="text" class="form-control" style="width: 400px;" value="{{ $profile->profile_name }}" />
                       </div>
                       <div style="margin-top: 20px;">
                            <label for="name">Description</label>
                            <input id="description" name="description" type="text" class="form-control" style="width: 400px;" value="{{ $profile->description }}" />
                       </div>
                       <div style="margin: auto;margin-top: 20px;width: 90px;">
                            <label for="type">Type</label>
                            <select id="type" name="type" class="form-control" style="width: 90px;" onchange="onThresholdProfileTypeChange()">
                                    <option value="CPU"  @if ($profile->profile_type == "CPU") selected="selected" @endif>CPU</option>
                                    <option value="Memory"  @if ($profile->profile_type == "Memory") selected="selected" @endif>Memory</option>
                            </select>
                        </div>
                        <div style="margin-top: 20px;">
                            <table align="center">
                                <tr>
                                    <td>
                                        <label for="warninglevel">Warning Level</label>
                                        <div>
                                            <input id="warninglevel" name="warninglevel" type="text" class="form-control" style="width: 80px;float: left;text-align: right;" value="{{ $profile->warning_level }}" />
                                            <label id="warningleveltype" style="float: left;line-height: 36px;"> @if ($profile->profile_type == "Memory")%@endif</label>
                                            <div style="clear: both;"></div>
                                        </div>
                                    </td>
                                    <td style="padding-left: 30px;">
                                        <label for="errorlevel">Error Level</label>
                                        <div>
                                            <input id="errorlevel" name="errorlevel" type="text" class="form-control" style="width: 80px;float: left;text-align: right;" value="{{ $profile->error_level }}" />
                                            <label id="errorleveltype" style="float: left;line-height: 36px;"> @if ($profile->profile_type == "Memory")%@endif</label>
                                            <div style="clear: both;"></div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                       </div>
                       <div class="form-group" style="width: 161px;margin:auto;margin-top: 20px;">
                            <button id="changebtn" name="changebtn" type="submit" class="btn btn-primary">Change</button>
                            <button id="cancelbtn" name="cancelbtn" type="submit" class="btn btn-info">Cancel</button>
                        </div>
                        {{ Form::close() }}
                </div>
                <div class="card-footer">&nbsp;
                </div>
            </div>

    </div>
</div>
@endsection
