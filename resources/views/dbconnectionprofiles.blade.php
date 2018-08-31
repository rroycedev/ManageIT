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
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Database Connection Profiles</div>

                <div class="card-body">

                    <table id="example" class="table table-striped table-bordered"  style="width: 600px;margin: auto;">
                        <thead>
                            <tr>
                                <th>Server Group</th>
                                <th>&nbsp;</th>
                                <th>&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($profiles as $profile)
                        <tr>
                            <td style="width: 400px;">{{ $profile->server_group_name }}</td>
                            <td style="width: 80px;"><a class="btn btn-primary" href="{{ url("dbconnectionprofiles/change/" . $profile->server_group_id)  }}" >Change</a></td>
                            <td style="width: 80px;"><a class="btn btn-primary" href="{{ url("dbconnectionprofiles/delete/" . $profile->server_group_id) }}" >Delete</a></td>
                        </tr>
                        @endforeach

                        </tbody>
                    </table>

                </div>
                <div class="card-footer">
                    <div style="width: 110px;margin: auto;"><a class="btn btn-primary" href="{{ url("dbconnectionprofiles/add") }}" >Add</a></div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
