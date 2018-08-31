<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        try {
            $servergroups = DB::table('server_groups')
                ->orderBy('server_group_name', 'asc')
                ->get();
        } catch (\Exception $ex) {
            $validator->errors()->add('insert', $ex->getMessage());
            return redirect('servergroups')
                ->withErrors($validator)
                ->withInput();
        }

        return view('servergroups', ["servergroups" => $servergroups]);
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

    public function insert(Request $request)
    {
        $params = $request->all();

        if (array_key_exists("donebtn", $params)) {
            return redirect('servergroups');
        }

        $name = $request->input('name');
        $description = $request->input('description');

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:60',
            'description' => 'required|max:60',
        ]);

        if ($validator->fails()) {
            return redirect('servergroups/add')
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $serverGroup = DB::table('server_groups')
                ->select('server_group_id')
                ->where(array('server_group_name' => $name, "description" => $description))
                ->get();

            if ($serverGroup && count($serverGroup) > 0) {
                $validator->errors()->add('name', 'Server group already exists with that name');
                return redirect('servergroups/add')
                    ->withErrors($validator)
                    ->withInput();
            }
        } catch (\Exception $ex) {
            $validator->errors()->add('insert', $ex->getMessage());
            return redirect('servergroups/add')
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::table('server_groups')->insert(
                ['server_group_name' => $name, "description" => $description]

            );
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

        try {
            $serverGroups = DB::table('server_groups')
                ->select('*')
                ->where(array('server_group_id' => $serverGroupId))
                ->get();

        } catch (\Exception $ex) {
            $validator->errors()->add('insert', $ex->getMessage());
            return redirect('servergroups')
                ->withErrors($validator)
                ->withInput();
        }

        $serverGroup = $serverGroups[0];

        return view('servergroups.change', ["servergroup" => $serverGroup]);
    }

    public function update(Request $request)
    {
        $name = $request->input('name');
        $description = $request->input('description');

        $serverGroupId = $request->input('server_group_id');

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:60',
            'description' => 'required|max:60',
        ]);

        if ($validator->fails()) {
            return redirect('servergroups/change/' . $serverGroupId)
                ->withErrors($validator)
                ->withInput();
        }
        try {
            $serverGroups = DB::table('server_groups')
                ->select('server_group_id', 'server_group_name', 'description')
                ->where(array('server_group_name' => $name))
                ->get();

        } catch (\Exception $ex) {
            $validator->errors()->add('insert', $ex->getMessage());
            return redirect('servergroups')
                ->withErrors($validator)
                ->withInput();
        }

        if (count($serverGroups) > 0) {
            if ($serverGroups[0]->server_group_id != $serverGroupId) {
                $validator->errors()->add('name', 'Server group already exists with that name');
                return redirect('servergroups/change/' . $serverGroupId)
                    ->withErrors($validator)
                    ->withInput();
            }
        }

        try {
            DB::table('server_groups')->where('server_group_id', $serverGroupId)
                ->update(['server_group_name' => $name, "description" => $description]
                );
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

        try {
            $serverGroups = DB::table('server_groups')
                ->select('server_group_id', 'server_group_name')
                ->where(array('server_group_id' => $serverGroupId))
                ->get();

        } catch (\Exception $ex) {
            $validator->errors()->add('insert', $ex->getMessage());
            return redirect('servergroups')
                ->withErrors($validator)
                ->withInput();
        }

        $serverGroup = $serverGroups[0];

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

        try {
            DB::table('server_groups')->where('server_group_id', "=", $serverGroupId)
                ->delete();
        } catch (\Exception $ex) {
            $validator->errors()->add('insert', $ex->getMessage());
            return redirect('servergroups/delete/' . $serverGroupId)
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::table('servers')->where('server_group_id', "=", $serverGroupId)
                ->delete();
            return redirect('servergroups')->with('message', 'Server group was deleted successfully');
        } catch (\Exception $ex) {
            $validator->errors()->add('insert', $ex->getMessage());
            return redirect('servergroups/delete/' . $serverGroupId)
                ->withErrors($validator)
                ->withInput();
        }
    }
}
