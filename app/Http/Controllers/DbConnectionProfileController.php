<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class DbConnectionProfileController extends Controller
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
            $profiles = DB::table('dbconnection_profiles')
                ->select('dbconnection_profiles.server_group_id', 'dbconnection_profiles.username', 'server_groups.server_group_name')
                ->join('server_groups', 'dbconnection_profiles.server_group_id', '=', 'server_groups.server_group_id')
                ->orderBy('server_groups.server_group_name', 'asc')
                ->get();
        } catch (\Exception $ex) {
            $validator->errors()->add('insert', $ex->getMessage());
            return redirect('dbconnectionprofiles')
                ->withErrors($validator)
                ->withInput();
        }

        return view('dbconnectionprofiles', ["profiles" => $profiles]);
    }

    private function makeValidator(Request $request, $wantPassword = true)
    {
        $rules = array('username' => 'required|max:60');

        if ($wantPassword) {
            $rules['password'] = 'required|max:60';
        }

        $validator = Validator::make($request->all(), $rules);

        return $validator;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function add()
    {
        // Get server groups

        try {
            $groups = DB::table('server_groups')
                ->select('server_group_id', 'server_group_name')
                ->orderBy('server_group_name', 'asc')
                ->get();
        } catch (\Exception $ex) {
            $validator->errors()->add('insert', $ex->getMessage());
            return redirect('dbconnectionprofiles')
                ->withErrors($validator)
                ->withInput();
        }

        return view('dbconnectionprofiles.add', ['groups' => $groups]);
    }
    public function insert(Request $request)
    {
        $params = $request->all();

        if (array_key_exists("donebtn", $params)) {
            return redirect('dbconnectionprofiles');
        }

        $serverGroupId = $request->input('group');
        $username = $request->input('username');
        $password = $request->input('password');
        $port = $request->input('port');
        if ($port == "") {
            $port = "3306";
        }

        $validator = $this->makeValidator($request);

        if ($validator->fails()) {
            return redirect('dbconnectionprofiles/add')
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $profiles = DB::table('dbconnection_profiles')
                ->select('*')
                ->where(array('server_group_id' => $serverGroupId))
                ->get();

            if (count($profiles) > 0) {
                $validator->errors()->add('name', 'Profile already exists for that server group');
                return redirect('dbconnectionprofiles/add')
                    ->withErrors($validator)
                    ->withInput();
            }
        } catch (\Exception $ex) {
            $validator->errors()->add('insert', $ex->getMessage());
            return redirect('dbconnectionprofiles/add')
                ->withErrors($validator)
                ->withInput();
        }

        $secretKey = env('DBCONNECTION_PROFILE_PASSWORD_SECRET_KEY');

        try {
            DB::insert('insert into dbconnection_profiles (server_group_id, username, password, port) values (' . $serverGroupId . ', \'' . $username . '\', DES_ENCRYPT(\'' .
                $password . '\', \'' . $secretKey . '\'), ' . $port . ')');
            return redirect()->back()->with('message', 'Database connection profile was added successfully');
        } catch (\Exception $ex) {
            $validator->errors()->add('insert', str_replace($password, 'XXXX', str_replace($secretKey, 'XXXXX', $ex->getMessage())));
            return redirect('dbconnectionprofiles/add')
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

        try {
            $groups = DB::table('server_groups')
                ->select('server_group_id', 'server_group_name')
                ->orderBy('server_group_name', 'asc')
                ->get();
        } catch (\Exception $ex) {
            $validator->errors()->add('insert', $ex->getMessage());
            return redirect('dbconnectionprofiles')
                ->withErrors($validator)
                ->withInput();
        }

        $secretKey = env('DBCONNECTION_PROFILE_PASSWORD_SECRET_KEY');

        try {
            $profiles = DB::select('SELECT a.server_group_id, a.username, DES_DECRYPT(a.password, \'' . $secretKey . '\') as password, a.port, b.server_group_name FROM dbconnection_profiles a left join server_groups b using (server_group_id) WHERE a.server_group_id = ' . $serverGroupId);

        } catch (\Exception $ex) {
            $validator->errors()->add('insert', $ex->getMessage());
            return redirect('dbconnectionprofiles')
                ->withErrors($validator)
                ->withInput();
        }

        $profile = $profiles[0];

        return view('dbconnectionprofiles.change', ["profile" => $profile, "groups" => $groups]);
    }

    public function update(Request $request)
    {
        $params = $request->all();

        if (array_key_exists("cancelbtn", $params)) {
            return redirect('dbconnectionprofiles');
        }

        $serverGroupId = $request->input('group');
        $username = $request->input('username');
        $password = $request->input('password');
        $port = $request->input('port');
        if ($port == "") {
            $port = "3306";
        }

        $validator = $this->makeValidator($request, false);

        if ($validator->fails()) {
            return redirect('dbconnectionprofiles/change/' . $serverGroupId)
                ->withErrors($validator)
                ->withInput();
        }

        $secretKey = env('DBCONNECTION_PROFILE_PASSWORD_SECRET_KEY');

        $sql = "update dbconnection_profiles set username = '$username', port = $port";

        if ($password != "") {
            $sql .= ", password = DES_ENCRYPT('$password', '$secretKey')";
        }

        $sql .= " WHERE server_group_id = $serverGroupId";

        try {
            DB::update($sql);
            return redirect('dbconnectionprofiles')->with('message', 'Database connection profile was updated successfully');
        } catch (\Exception $ex) {
            $validator->errors()->add('insert', str_replace($password, 'XXXX', str_replace($secretKey, 'XXXXX', $ex->getMessage())));
            return redirect('dbconnectionprofiles/change/' . $serverGroupId)
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
        $profileId = $request->route('profileid');

        $validator = Validator::make($request->all(), [
        ]);

        try {
            $profiles = DB::table('threshold_profiles')
                ->select('*')
                ->where(array('profile_id' => $profileId))
                ->get();

        } catch (\Exception $ex) {
            $validator->errors()->add('insert', $ex->getMessage());
            return redirect('dbconnectionprofiles')
                ->withErrors($validator)
                ->withInput();
        }

        $profile = $profiles[0];

        return view('dbconnectionprofiles.delete', ["profile" => $profile]);
    }

    public function remove(Request $request)
    {
        $params = $request->all();

        if (array_key_exists("cancelbtn", $params)) {
            return redirect('dbconnectionprofiles');
        }

        $profileId = $request->input("profile_id");

        $validator = Validator::make($request->all(), [
        ]);

        try {
            DB::table('threshold_profiles')->where('profile_id', "=", $profileId)
                ->delete();
        } catch (\Exception $ex) {
            $validator->errors()->add('insert', $ex->getMessage());
            return redirect('dbconnectionprofiles/delete/' . $profileId)
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::table('threshold_profiles')->where('profile_id', "=", $profileId)
                ->delete();
            return redirect('dbconnectionprofiles')->with('message', 'Threshold profile was deleted successfully');
        } catch (\Exception $ex) {
            $validator->errors()->add('insert', $ex->getMessage());
            return redirect('dbconnectionprofiles/delete/' . $profileId)
                ->withErrors($validator)
                ->withInput();
        }
    }
}
