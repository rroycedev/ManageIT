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
        <div>
            <div class="card" style="width: 725px;">
                <div class="card-header">Change Server</div>

                <div class="card-body">
                {{ Form::open(array('url' => 'servers/update')) }}
                        <input type="hidden" id="server_id" name="server_id" value="{{ $server->server_id }}" />
                        <table>
                            <tr>
                                <td style="vertical-align: top;">
                                    <div>
                                        <label for="server_name">Name</label>
                                        <input id="server_name" name="server_name" type="text" class="form-control" style="width: 400px;" value="{{ $server->server_name }}" />
                                    </div>
                                    <div style="margin-top: 20px;">
                                        <label for="server_type">Type</label>
                                        <select id="server_type" name="server_type" class="form-control " style="width: 146px;">
                                                <option value="Application Server"  @if ($server->server_type == "Application Server") selected="selected" @endif>Application Server</option>
                                                <option value="Database Server"  @if ($server->server_type == "Database Server") selected="selected" @endif>Database Server</option>
                                        </select>
                                    </div>
                                    <div style="margin-top: 20px;">
                                        <label for="hostname">Hostname</label>
                                        <input id="hostname" name="hostname" type="text" class="form-control" style="width: 400px;" value="{{ $server->hostname }}" />
                                    </div>
                                </td>
                                <td style="vertical-align: top;padding-left: 100px;">
                                    <div>
                                        <label for="server_group_id">Group</label>
                                        <select id="server_group_id" name="server_group_id" class="form-control ">
                                            <?php
foreach ($groups as $group) {
    ?>
                                                <option value="{{ $group->server_group_id }}"  @if ($server->server_group_id == $group->server_group_id) selected="selected" @endif>{{ $group->server_group_name }}</option>
                                            <?php
}
?>
                                        </select>
                                    </div>
                                    <div style="margin-top: 20px;">
                                        <label for="environment">Environment</label>
                                        <select id="environment" name="environment" class="form-control ">
                                                <option value="Development" @if ($server->environment == "Development") selected="selected" @endif>Development</option>
                                                <option value="QA"  @if ($server->environment == "QA") selected="selected" @endif>QA</option>
                                                <option value="Staging"  @if ($server->environment == "Staging") selected="selected" @endif>Staging</option>
                                                <option value="Production"  @if ($server->environment == "Production") selected="selected" @endif>Production</option>
                                        </select>
                                    </div>
                                    <div style="margin-top: 20px;">
                                        <label for="status">Status</label>
                                        <select id="status" name="status" class="form-control ">
                                                <option value="Not Monitored" @if ($server->status == "Not Monitored") selected="selected" @endif>Not Monitored</option>
                                                <option value="Monitored" @if ($server->status == "Monitored") selected="selected" @endif>Monitored</option>
                                                <option value="Maintenance" @if ($server->status == "Maintenance") selected="selected" @endif>Maintenance</option>
                                        </select>
                                    </div>
                                </td>
                            </tr>
                        </table>
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
</div>
@endsection
