<?php

namespace App\Http\Controllers;

use App\ServerGroup;
use Illuminate\Http\Request;
use Validator;

class ServerGroupsController extends Controller
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
        $serverGroupModel = new ServerGroup();

        try {
            $groups = $serverGroupModel->get();
        } catch (\Exception $ex) {
            $validator->errors()->add('insert', $ex->getMessage());
            return redirect('servers')
                ->withErrors($validator)
                ->withInput();
        }

        return view('servergroups', ["servergroups" => $groups]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function add()
    {
        return view('servergroups.add');
    }

    private function requestToModel(Request $request)
    {
        $serverGroupModel = new ServerGroup();

        $serverGroupModel->server_group_id = $request->input('server_group_id');
        $serverGroupModel->server_group_name = $request->input('server_group_name');
        $serverGroupModel->description = $request->input('description');

        return $serverGroupModel;
    }

    public function insert(Request $request)
    {
        $params = $request->all();

        if (array_key_exists("donebtn", $params)) {
            return redirect('servergroups');
        }

        $serverGroupModel = $this->requestToModel($request);

        $validator = $serverGroupModel->validate($request);

        if ($validator->fails()) {
            return redirect('servergroups/add')
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $groups = $serverGroupModel->getByName($serverGroupModel->server_group_name);

            if (count($groups) > 0) {
                $validator->errors()->add('name', 'Server group already exists with that name');
                return redirect('servergroups/add')
                    ->withErrors($validator)
                    ->withInput();
            }
        } catch (\Exception $ex) {
            $validator->errors()->add('insert', $ex->getMessage());
            return redirect('servers')
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $serverGroupModel->insertRow();

            return redirect()->back()->with('message', 'Server group was added successfully');
        } catch (\Exception $ex) {
            $validator->errors()->add('insert', $ex->getMessage());
            return redirect('servergroups/add')
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
        $serverGroupId = $request->route('servergroupid');

        $validator = Validator::make($request->all(), [
        ]);

        $serverGroupModel = new ServerGroup();

        try {
            $groups = $serverGroupModel->get($serverGroupId);
        } catch (\Exception $ex) {
            $validator->errors()->add('insert', $ex->getMessage());
            return redirect('servers')
                ->withErrors($validator)
                ->withInput();
        }

        $serverGroup = $groups[0];

        return view('servergroups.change', ["servergroup" => $serverGroup]);
    }

    public function update(Request $request)
    {
        $serverGroupModel = $this->requestToModel($request);

        $validator = $serverGroupModel->validate($request);

        if ($validator->fails()) {
            return redirect('servergroups/change/' . $serverGroupId)
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $groups = $serverGroupModel->getByName($serverGroupModel->server_group_name);

            if (count($groups) > 0) {
                if ($serverGroupModel->server_group_id != $groups[0]->server_group_id) {
                    $validator->errors()->add('name', 'Server group already exists with that name');
                    return redirect('servergroups/add')
                        ->withErrors($validator)
                        ->withInput();
                }
            }
        } catch (\Exception $ex) {
            $validator->errors()->add('insert', $ex->getMessage());
            return redirect('servers')
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $serverGroupModel->updateRow();

            return redirect('servergroups')->with('message', 'Server group was updated successfully');
        } catch (\Exception $ex) {
            $validator->errors()->add('insert', $ex->getMessage());
            return redirect('servergroups/change/' . $serverGroupId)
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
        $serverGroupId = $request->route('servergroupid');

        $validator = Validator::make($request->all(), [
        ]);

        $serverGroupModel = new ServerGroup();

        try {
            $groups = $serverGroupModel->get($serverGroupId);
        } catch (\Exception $ex) {
            $validator->errors()->add('insert', $ex->getMessage());
            return redirect('servers')
                ->withErrors($validator)
                ->withInput();
        }

        $serverGroup = $groups[0];

        return view('servergroups.delete', ["servergroup" => $serverGroup]);
    }

    public function remove(Request $request)
    {
        $params = $request->all();

        if (array_key_exists("cancelbtn", $params)) {
            return redirect('servergroups');
        }

        $serverGroupId = $request->input("server_group_id");

        $validator = Validator::make($request->all(), [
        ]);

        $serverGroupModel = new ServerGroup();

        try {
            $serverGroupModel->deleteRow($serverGroupId);

            return redirect('servergroups')->with('message', 'Server group was deleted successfully');
        } catch (\Exception $ex) {
            $validator->errors()->add('insert', $ex->getMessage());
            return redirect('servergroups/delete/' . $serverGroupId)
                ->withErrors($validator)
                ->withInput();
        }
    }
}
