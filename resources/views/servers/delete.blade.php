@extends('layouts.app')

@section('content')
@if ($errors->any())
    <div class="alert alert-danger" style="width: 80%;margin: auto;">
        <ul style="margin-top: 0px;margin-bottom: 0px;padding-top: 0px;padding-bottom: 0px;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<div class="container">
    <div class="row justify-content-center">

            <div class="card" style="width: 1000px;">
                <div class="card-header">Delete Server</div>

                <div class="card-body">
                {{ Form::open(array('url' => 'servers/remove')) }}
                    <input type="hidden" id="server_id" name="server_id" value="{{ $server->server_id }}" />
                        <div class="form-group">
                            <h6 style="text-align: center;">Are you sure you wish to delete server "{{$server->server_name}}"?</h6>
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
