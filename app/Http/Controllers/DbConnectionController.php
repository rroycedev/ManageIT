<?php

namespace App\Http\Controllers;

use App\DatabaseConnection;
use App\ServerGroup;
use Illuminate\Http\Request;
use Validator;

class DbConnectionController extends Controller
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
        $validator = Validator::make([], []);

        $dbConnection = new DatabaseConnection();

        try {
            $profiles = $dbConnection->get();
        } catch (\Exception $ex) {
            $validator->errors()->add('insert', $ex->getMessage());
            return redirect('/')
                ->withErrors($validator)
                ->withInput();
        }

        return view('dbconnections', ["profiles" => $profiles]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function add()
    {
        // Get server groups
        $serverGroup = new ServerGroup();

        try {
            $groups = $serverGroup->get();
        } catch (\Exception $ex) {
            $validator->errors()->add('insert', $ex->getMessage());
            return redirect('dbconnections')
                ->withErrors($validator)
                ->withInput();
        }

        return view('dbconnections.add', ['groups' => $groups]);
    }
    public function insert(Request $request)
    {
        $params = $request->all();

        if (array_key_exists("donebtn", $params)) {
            return redirect('dbconnections');
        }

        $serverGroupId = $request->input('group');
        $username = $request->input('username');
        $password = $request->input('password');
        $port = $request->input('port');
        if ($port == "") {
            $port = "3306";
        }

        $dbConnection = new DatabaseConnection();

        $dbConnection->server_group_id = $serverGroupId;
        $dbConnection->username = $username;
        $dbConnection->password = $password;
        $dbConnection->port = $port;

        $validator = $dbConnection->validate($request, true);

        if ($validator->fails()) {
            return redirect('dbconnections/add')
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $profiles = $dbConnection->get($serverGroupId);

            if (count($profiles) > 0) {
                $validator->errors()->add('name', 'Profile already exists for that server group');
                return redirect('dbconnections/add')
                    ->withErrors($validator)
                    ->withInput();
            }
        } catch (\Exception $ex) {
            $validator->errors()->add('insert', $ex->getMessage());
            return redirect('dbconnections/add')
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $dbConnection->insertRow();

            return redirect()->back()->with('message', 'Database connection profile was added successfully');
        } catch (\Exception $ex) {
            $validator->errors()->add('insert', str_replace($password, 'XXXX', str_replace($secretKey, 'XXXXX', $ex->getMessage())));
            return redirect('dbconnections/add')
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

        // Get server groups

        $serverGroup = new ServerGroup();

        try {
            $groups = $serverGroup->get();
        } catch (\Exception $ex) {
            $validator->errors()->add('insert', $ex->getMessage());
            return redirect('dbconnections')
                ->withErrors($validator)
                ->withInput();
        }

        $dbConnection = new DatabaseConnection();

        try {
            $profiles = $dbConnection->get($serverGroupId);
        } catch (\Exception $ex) {
            $validator->errors()->add('insert', $ex->getMessage());
            return redirect('dbconnections')
                ->withErrors($validator)
                ->withInput();
        }

        $profile = $profiles[0];

        return view('dbconnections.change', ["profile" => $profile, "groups" => $groups]);
    }

    public function update(Request $request)
    {
        $params = $request->all();

        if (array_key_exists("cancelbtn", $params)) {
            return redirect('dbconnections');
        }

        $dbConnection = new DatabaseConnection();

        $dbConnection->server_group_id = $serverGroupId;
        $dbConnection->username = $username;
        $dbConnection->password = $password;
        $dbConnection->port = $port;

        $validator = $dbConnection->validate($request, true);

        if ($validator->fails()) {
            return redirect('dbconnections/change/' . $serverGroupId)
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $dbConnection->updateRow();

            return redirect('dbconnections')->with('message', 'Database connection was updated successfully');
        } catch (\Exception $ex) {
            $validator->errors()->add('insert', str_replace($password, 'XXXX', str_replace($secretKey, 'XXXXX', $ex->getMessage())));
            return redirect('dbconnections/change/' . $serverGroupId)
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

        $dbConnection = new DatabaseConnection();

        try {
            $profiles = $dbConnection->get($serverGroupId);

        } catch (\Exception $ex) {
            $validator->errors()->add('insert', $ex->getMessage());
            return redirect('dbconnections')
                ->withErrors($validator)
                ->withInput();
        }

        $profile = $profiles[0];

        return view('dbconnections.delete', ["profile" => $profile]);
    }

    public function remove(Request $request)
    {
        $params = $request->all();

        if (array_key_exists("cancelbtn", $params)) {
            return redirect('dbconnections');
        }

        $profileId = $request->input("server_group_id");

        $validator = Validator::make($request->all(), [
        ]);

        $dbConnection = new DatabaseConnection();

        try {
            $dbConnection->deleteRow($serverGroupId);

            return redirect('dbconnections')->with('message', 'Threshold profile was deleted successfully');
        } catch (\Exception $ex) {
            $validator->errors()->add('insert', $ex->getMessage());
            return redirect('dbconnections/delete/' . $serverGroupId)
                ->withErrors($validator)
                ->withInput();
        }
    }
}
