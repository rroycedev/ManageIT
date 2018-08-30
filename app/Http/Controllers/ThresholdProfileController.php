<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class ThresholdProfileController extends Controller
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
        $thresholdProfiles = DB::table('threshold_profiles')
            ->orderBy('profile_name', 'asc')
            ->get();

        return view('thresholdprofiles', ["profiles" => $thresholdProfiles]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function add()
    {
        return view('thresholdprofiles.add');
    }
    public function insert(Request $request)
    {
        $name = $request->input('name');
        $description = $request->input('description');

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:60',
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
            return redirect('thresholdprofiles')
                ->withErrors($validator)
                ->withInput();
        }

        $profile = $profiles[0];

        return view('thresholdprofiles.change', ["profile" => $profile]);
    }

    public function update(Request $request)
    {
        $params = $request->all();

        if (array_key_exists("cancelbtn", $params)) {
            return redirect('thresholdprofiles');
        }

        $name = $request->input('name');
        $description = $request->input('description');
        $type = $request->input('type');
        $warning = $request->input('warninglevel');
        $error = $request->input('errorlevel');

        $profileId = $request->input('profile_id');

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:60',
            'description' => 'required|max:60',
            'warninglevel' => 'required',
            'errorlevel' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('thresholdprofiles/change/' . $profileId)
                ->withErrors($validator)
                ->withInput();
        }
        try {
            $profiles = DB::table('threshold_profiles')
                ->select('*')
                ->where(array('profile_name' => $name))
                ->get();

        } catch (\Exception $ex) {
            $validator->errors()->add('insert', $ex->getMessage());
            return redirect('thresholdprofiles/change/' . $profileId)
                ->withErrors($validator)
                ->withInput();
        }

        if (count($profiles) > 0) {
            if ($profiles[0]->profile_id != $profileId) {
                $validator->errors()->add('name', 'Server group already exists with that name');
                return redirect('thresholdprofiles/change/' . $profileId)
                    ->withErrors($validator)
                    ->withInput();
            }
        }

        try {
            DB::table('threshold_profiles')->where('profile_id', $profileId)
                ->update(['profile_name' => $name, "description" => $description, "profile_type" => $type, "warning_level" => $warning, "error_level" => $error]
                );
            return redirect('thresholdprofiles')->with('message', 'Threshold profile was updated successfully');
        } catch (\Exception $ex) {
            $validator->errors()->add('insert', $ex->getMessage());
            return redirect('thresholdprofiles/change/' . $profileId)
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
            return redirect('servergroups/add')
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
