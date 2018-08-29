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
                <div class="card-header">Add Server</div>

                <div class="card-body">
                {{ Form::open(array('url' => 'servers/insert')) }}
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input id="name" name="name" type="text" class="form-control" />
                       </div>
                       <div class="form-group">
                            <label for="type">Type</label>
                            <select id="type" name="type" class="form-control ">
                                    <option value="Application Server">Application Server</option>
                                    <option value="Database Server">Database Server</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="environment">Environment</label>
                            <select id="environment" name="environment" class="form-control ">
                                    <option value="Development">Development</option>
                                    <option value="QA">QA</option>
                                    <option value="Staging">Staging</option>
                                    <option value="Production">Production</option>
                            </select>
                        </div>
                        <div class="form-group">
                                <label for="hostname">Hostname</label>
                            <input id="hostname" name="hostname" type="text" class="form-control" />
                         </div>
                        <div class="form-group">
                            <label for="port">Port</label>
                            <input id="port" type="text" class="form-control " />
                        </div>
                        <div class="form-group">
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