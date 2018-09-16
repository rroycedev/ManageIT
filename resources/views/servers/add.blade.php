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

        <div class="card" style="width: 725px;">
                <div class="card-header">Add Server</div>

                <div class="card-body">
                {{ Form::open(array('url' => 'servers/insert')) }}
                <table>
                            <tr>
                                <td style="vertical-align: top;">
                                    <div>
                                        <label for="server_name">Name</label>
                                        <input id="server_name" name="server_name" type="text" class="form-control" style="width: 400px;" value="" />
                                    </div>
                                    <div style="margin-top: 20px;">
                                        <label for="server_type">Type</label>
                                        <select id="server_type" name="server_type" class="form-control " style="width: 146px;">
                                                <option value="Application Server">Application Server</option>
                                                <option value="Database Server">Database Server</option>
                                        </select>
                                    </div>
                                    <div style="margin-top: 20px;">
                                        <label for="hostname">Hostname</label>
                                        <input id="hostname" name="hostname" type="text" class="form-control" style="width: 400px;" value="" />
                                    </div>
                                    <div style="margin-top: 20px;">
                                        <label for="server_threshold_profile_id">Server Threshold Profile</label>
                                        <select id="server_threshold_profile_id" name="server_threshold_profile_id" class="form-control " style="width: 146px;">
                                        @foreach ($server_threshold_profiles as $profile)
                                            <option value="{{ $profile->server_threshold_profile_id }}">{{ $profile->profile_name }}</option>
                                        @endforeach
                                        </select>
                                    </div>

                                </td>
                                <td style="vertical-align: top;padding-left: 100px;">
                                    <div>
                                        <label for="server_group_id">Group</label>
                                        <select id="server_group_id" name="server_group_id" class="form-control ">
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
                                        <label for="environment">Environment</label>
                                        <select id="environment" name="environment" class="form-control ">
                                                <option value="Development">Development</option>
                                                <option value="QA">QA</option>
                                                <option value="Staging">Staging</option>
                                                <option value="Production">Production</option>
                                        </select>
                                    </div>
                                    <div style="margin-top: 20px;">
                                        <label for="status">Status</label>
                                        <select id="status" name="status" class="form-control ">
                                                <option value="Not Monitored">Not Monitored</option>
                                                <option value="Monitored">Monitored</option>
                                                <option value="Maintenance">Maintenance</option>
                                        </select>
                                    </div>
                                    <div style="margin-top: 20px;display: none;">
                                        <label for="server_type">Database Threshold Profile</label>
                                        <select id="database_threshold_profile_id" name="database_threshold_profile_id" class="form-control " style="width: 146px;">
                                                <option value="Application Server">Application Server</option>
                                                <option value="Database Server">Database Server</option>
                                        </select>
                                    </div>

                                </td>
                            </tr>
                        </table>
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
