<?php

namespace App\Http\Controllers;

use App\models\Server;
use App\models\ServerGroup;
use Illuminate\Http\Request;
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
        $serverModel = new Server();

        try {
            $servers = $serverModel->get();
        } catch (\Exception $ex) {
            $validator->errors()->add('insert', $ex->getMessage());
            return redirect('servers')
                ->withErrors($validator)
                ->withInput();
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
        // Get server groups

        $serverGroupModel = new ServerGroup();

        try {
            $groups = $serverGroupModel->get();
        } catch (\Exception $ex) {
            $validator->errors()->add('insert', $ex->getMessage());
            return redirect('servers')
                ->withErrors($validator)
                ->withInput();
        }

        //  If no server groups don't allow adding server

        if (count($groups) == 0) {
            $validator = Validator::make([], [
            ]);
            $validator->errors()->add('name', 'No server groups are defined');
            return redirect('servers')
                ->withErrors($validator)
                ->withInput();
        }
        return view('servers.add', ["groups" => $groups]);
    }

    private function requestToModel(Request $request)
    {
        $serverModel = new Server();

        $serverModel->server_id = $request->input('server_id');
        $serverModel->server_name = $request->input('server_name');
        $serverModel->server_type = $request->input('server_type');
        $serverModel->environment = $request->input('environment');
        $serverModel->hostname = $request->input('hostname');
        $serverModel->status = $request->input('status');
        $serverModel->server_group_id = $request->input('server_group_id');

        return $serverModel;
    }

    public function insert(Request $request)
    {
        $params = $request->all();

        if (array_key_exists("donebtn", $params)) {
            return redirect('servers');
        }

        $serverModel = $this->requestToModel($request);

        $validator = $serverModel->validate($request);

        if ($validator->fails()) {
            return redirect('servers/add')
                ->withErrors($validator)
                ->withInput();
        }

        //  Validate unique key 'server_name'

        try {
            $servers = $serverModel->getByName($serverModel->server_name);

            if (count($servers) > 0) {
                $validator->errors()->add('server_name', 'Server already exists with that server name');
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

        //  Validate unique key 'hostname'

        try {
            $servers = $serverModel->getByHostname($serverModel->hostname);

            if (count($servers) > 0) {
                $validator->errors()->add('hostname', 'Server already exists with that hostname');
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

        //  Insert server

        try {
            $serverModel->insertRow();

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
        // Get server groups

        $serverGroupModel = new ServerGroup();

        try {
            $groups = $serverGroupModel->get();
        } catch (\Exception $ex) {
            $validator->errors()->add('insert', $ex->getMessage());
            return redirect('servers')
                ->withErrors($validator)
                ->withInput();
        }

        $serverId = $request->route('serverid');

        $validator = Validator::make($request->all(), [
        ]);

        //  Retrieve server info

        $serverModel = new Server();

        try {
            $servers = $serverModel->get($serverId);
        } catch (\Exception $ex) {
            $validator->errors()->add('insert', $ex->getMessage());
            return redirect('servers')
                ->withErrors($validator)
                ->withInput();
        }

        $server = $servers[0];

        return view('servers.change', ["server" => $server, "groups" => $groups]);
    }

    public function update(Request $request)
    {
        $params = $request->all();

        if (array_key_exists("cancelbtn", $params)) {
            return redirect('servers');
        }

        $serverModel = $this->requestToModel($request);

        $validator = $serverModel->validate($request);

        if ($validator->fails()) {
            return redirect('servers/change/' . $serverModel->server_id)
                ->withErrors($validator)
                ->withInput();
        }

        //  Validate unique key 'server_name'

        try {
            $servers = $serverModel->getByName($serverModel->server_name);

            if (count($servers) > 0) {
                if ($servers[0]->server_id != $serverModel->server_id) {
                    $validator->errors()->add('name', 'Server already exists with that server name');
                    return redirect('servers/add')
                        ->withErrors($validator)
                        ->withInput();
                }
            }
        } catch (\Exception $ex) {
            $validator->errors()->add('insert', $ex->getMessage());
            return redirect('servers/add')
                ->withErrors($validator)
                ->withInput();
        }

        //  Validate unique key 'hostname'

        try {
            $servers = $serverModel->getByHostname($serverModel->hostname);

            if (count($servers) > 0) {
                if ($servers[0]->server_id != $serverModel->server_id) {
                    $validator->errors()->add('name', 'Server already exists with that hostname');
                    return redirect('servers/add')
                        ->withErrors($validator)
                        ->withInput();
                }
            }
        } catch (\Exception $ex) {
            $validator->errors()->add('insert', $ex->getMessage());
            return redirect('servers/add')
                ->withErrors($validator)
                ->withInput();
        }

        //  Update server

        try {
            $serverModel->updateRow();

            return redirect('servers')->with('message', 'Server was updated successfully');
        } catch (\Exception $ex) {
            $validator->errors()->add('insert', $ex->getMessage());
            return redirect('servers/change/' . $serverModel->server_id)
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

        $serverModel = new Server();

        try {
            $servers = $serverModel->get($serverId);
        } catch (\Exception $ex) {
            $validator->errors()->add('insert', $ex->getMessage());
            return redirect('servers')
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $servers = $serverModel->get($serverId);
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

        $serverModel = new Server();

        try {
            $serverModel->deleteRow($serverId);

            return redirect('servers')->with('message', 'Server was deleted successfully');
        } catch (\Exception $ex) {
            $validator->errors()->add('insert', $ex->getMessage());
            return redirect('servers/delete/' . $serverId)
                ->withErrors($validator)
                ->withInput();
        }
    }
}
