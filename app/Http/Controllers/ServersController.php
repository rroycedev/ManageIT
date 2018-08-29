<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class ServersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $servers = DB::table('servers')
            ->select('servers.server_id', 'servers.server_name', 'servers.server_type', 'servers.environment', 'servers.hostname', 'servers.port', 'server_groups.server_group_name')
            ->join('server_groups', 'servers.server_group_id', '=', 'server_groups.server_group_id')
            ->orderBy('servers.server_name', 'asc')
            ->get();

        for ($i = 0; $i < count($servers); $i++) {
            if ($servers[$i]->port == "0") {
                $servers[$i]->port = "";
            }
        }
        return view('servers', ["servers" => $servers]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function add()
    {
        $groups = DB::table('server_groups')
            ->select('server_group_id', 'server_group_name')
            ->orderBy('server_group_name', 'asc')
            ->get();

        return view('servers.add', ["groups" => $groups]);
    }

    public function insert(Request $request)
    {
        $name = $request->input('name');
        $type = $request->input('type');
        $env = $request->input('environment');
        $hostname = $request->input('hostname');
        $port = $request->input('port');
        if ($port == "") {
            $port = "0";
        }

        $serverGroupId = $request->input('group');

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:60',
            'hostname' => 'required|max:60',
        ]);

        if ($validator->fails()) {
            return redirect('servers/add')
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $server = DB::table('servers')
                ->select('server_id')
                ->where(array('server_name' => $name))
                ->get();

            if ($server && count($server) > 0) {
                $validator->errors()->add('name', 'Server already exists with that server name');
                return redirect('servers/add')
                    ->withErrors($validator)
                    ->withInput();
            }
        } catch (\Exception $ex) {
            $validator->errors()->add('insert', $ex->getMessage());
            return redirect('servers/add')
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $server = DB::table('servers')
                ->select('server_id')
                ->where(array('hostname' => $hostname, 'port' => $port))
                ->get();

            if ($server && count($server) > 0) {
                $validator->errors()->add('name', 'Server already exists with that hostname and port combination');
                return redirect('servers/add')
                    ->withErrors($validator)
                    ->withInput();
            }
        } catch (\Exception $ex) {
            $validator->errors()->add('insert', $ex->getMessage());
            return redirect('servers/add')
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::table('servers')->insert(
                ['server_name' => $name, 'server_type' => $type, 'environment' => $env, 'hostname' => $hostname, 'port' => $port, "server_group_id" => $serverGroupId]

            );
            return redirect()->back()->with('message', 'Server was added successfully');
        } catch (\Exception $ex) {
            $validator->errors()->add('insert', $ex->getMessage());
            return redirect('servers/add')
                ->withErrors($validator)
                ->withInput();
        }
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function change(Request $request)
    {
        $groups = DB::table('server_groups')
            ->select('server_group_id', 'server_group_name')
            ->orderBy('server_group_name', 'asc')
            ->get();

        $serverId = $request->route('serverid');

        $validator = Validator::make($request->all(), [
        ]);

        try {
            $servers = DB::table('servers')
                ->select('servers.server_id', 'servers.server_name', 'servers.server_type', 'servers.environment', 'servers.hostname', 'servers.port',
                    'servers.server_group_id', 'server_groups.server_group_name')
                ->join('server_groups', 'servers.server_group_id', '=', 'server_groups.server_group_id')
                ->where(array('servers.server_id' => $serverId))
                ->get();

        } catch (\Exception $ex) {
            $validator->errors()->add('insert', $ex->getMessage());
            return redirect('servers/add')
                ->withErrors($validator)
                ->withInput();
        }

        $server = $servers[0];
        if ($server->port == "0") {
            $server->port = "";
        }

        return view('servers.change', ["server" => $server, "groups" => $groups]);
    }

    public function update(Request $request)
    {
        $serverId = $request->input("server_id");
        $name = $request->input('name');
        $type = $request->input('type');
        $env = $request->input('environment');
        $hostname = $request->input('hostname');
        $port = $request->input('port');
        if ($port == "") {
            $port = "0";
        }
        $serverGroupId = $request->input('group');

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:60',
            'hostname' => 'required|max:60',
        ]);

        if ($validator->fails()) {
            return redirect('servers/change/' . $serverId)
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::table('servers')->where('server_id', $serverId)
                ->update(['server_name' => $name, 'server_type' => $type, 'environment' => $env, 'hostname' => $hostname, 'port' => "$port", "server_group_id" => $serverGroupId]
                );
            return redirect()->back()->with('message', 'Server was updated successfully');
        } catch (\Exception $ex) {
            $validator->errors()->add('insert', $ex->getMessage());
            return redirect('servers/change/' . $serverId)
                ->withErrors($validator)
                ->withInput();
        }
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        $serverId = $request->route('serverid');
        try {
            $servers = DB::table('servers')
                ->select('servers.server_id', 'servers.server_name', 'servers.server_type', 'servers.environment', 'servers.hostname', 'servers.port',
                    'servers.server_group_id', 'server_groups.server_group_name')
                ->join('server_groups', 'servers.server_group_id', '=', 'server_groups.server_group_id')
                ->where(array('servers.server_id' => $serverId))
                ->get();

        } catch (\Exception $ex) {
            $validator->errors()->add('insert', $ex->getMessage());
            return redirect('servers')
                ->withErrors($validator)
                ->withInput();
        }

        $server = $servers[0];

        return view('servers.delete', ["server" => $server]);
    }

    public function remove(Request $request)
    {
        $params = $request->all();

        if (array_key_exists("cancelbtn", $params)) {
            return redirect('servers');
        }

        $serverId = $request->input("server_id");

        try {
            DB::table('servers')->where('server_id', "=", $serverId)
                ->delete();
            return redirect('servers')->with('message', 'Server was deleted successfully');
        } catch (\Exception $ex) {
            $validator->errors()->add('insert', $ex->getMessage());
            return redirect('servers/delete/' . $serverId)
                ->withErrors($validator)
                ->withInput();
        }
    }
}
