<?php

namespace App\Http\Controllers;

use App\models\DatabaseThresholdParamNames;
use App\models\DatabaseThresholdProfile;
use Illuminate\Http\Request;
use Validator;

class DatabaseThresholdProfileController extends Controller
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
        $databaseThresholdProfileModel = new DatabaseThresholdProfile();

        try {
            $profiles = $databaseThresholdProfileModel->get();
        } catch (\Exception $ex) {
            $validator->errors()->add('insert', $ex->getMessage());
            return redirect('databasethresholdprofiles')
                ->withErrors($validator)
                ->withInput();
        }

        return view('databasethresholdprofiles', ["profiles" => $profiles]);
    }

    private function requestToModel(Request $request)
    {
        $databaseThresholdProfileModel = new DatabaseThresholdProfile();

        $databaseThresholdProfileModel->database_threshold_profile_id = $request->input('database_threshold_profile_id');
        $databaseThresholdProfileModel->profile_name = $request->input('profile_name');
        $databaseThresholdProfileModel->description = $request->input('description');

        return $databaseThresholdProfileModel;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function add()
    {
        $paramNamesModel = new DatabaseThresholdParamNames();

        $validator = Validator::make([], [
        ]);

        try {
            $paramNames = $paramNamesModel->get();

        } catch (\Exception $ex) {
            $validator->errors()->add('name', 'Error retrieving parameter names: ' . $ex->getMessage());
            return redirect('databasethresholdprofiles')
                ->withErrors($validator)
                ->withInput();
        }
        return view('databasethresholdprofiles.add', ["param_names" => $paramNames]);
    }
    public function insert(Request $request)
    {
        $paramNamesModel = new DatabaseThresholdParamNames();

        $validator = Validator::make([], [
        ]);

        try {
            $paramNames = $paramNamesModel->get();

        } catch (\Exception $ex) {
            $validator->errors()->add('name', 'Error retrieving parameter names: ' . $ex->getMessage());
            return redirect('databasethresholdprofiles')
                ->withErrors($validator)
                ->withInput();
        }
        $params = $request->all();

        if (array_key_exists("donebtn", $params)) {
            return redirect('databasethresholdprofiles');
        }

        $databaseThresholdProfileModel = $this->requestToModel($request);

        $validator = $databaseThresholdProfileModel->validate($request);

        if ($validator->fails()) {
            return redirect('databasethresholdprofiles/add')
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $profiles = $databaseThresholdProfileModel->getByName($databaseThresholdProfileModel->profile_name);

            if (count($profiles) > 0) {
                $validator->errors()->add('name', 'Profile already exists with that name');
                return redirect('databasethresholdprofiles/add')
                    ->withErrors($validator)
                    ->withInput();
            }
        } catch (\Exception $ex) {
            $validator->errors()->add('insert', $ex->getMessage());
            return redirect('databasethresholdprofiles/add')
                ->withErrors($validator)
                ->withInput();
        }

        $params = array();

        for ($i = 0; $i < count($paramNames); $i++) {
            $varname = "param_value_" . $paramNames[$i]->param_num;
            $paramValue = $request->input($varname);

            $param = (object) [];

            $param->param_name = $paramNames[$i]->param_name;
            $param->param_value = $paramValue;
            $param->param_label = $paramNames[$i]->param_label;
            $param->param_num = $paramNames[$i]->param_num;

            $params[] = $param;
        };

        $databaseThresholdProfileModel->params = $params;

        try {
            $databaseThresholdProfileModel->insertRow();

            return redirect()->back()->with('message', 'Database threshold profile was added successfully');
        } catch (\Exception $ex) {
            $validator->errors()->add('insert', $ex->getMessage());
            return redirect('databasethresholdprofiles/add')
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

        $databaseThresholdProfileModel = new DatabaseThresholdProfile();

        try {
            $profiles = $databaseThresholdProfileModel->get($profileId);
        } catch (\Exception $ex) {
            $validator->errors()->add('insert', "Error getting profile id $profileId : " . $ex->getMessage());
            return redirect('databasethresholdprofiles')
                ->withErrors($validator)
                ->withInput();
        }

        $profile = $profiles[0];

        return view('databasethresholdprofiles.change', ["profile" => $profile]);
    }

    public function update(Request $request)
    {
        $params = $request->all();

        if (array_key_exists("cancelbtn", $params)) {
            return redirect('databasethresholdprofiles');
        }

        $databaseThresholdProfileModel = $this->requestToModel($request);

        $validator = $databaseThresholdProfileModel->validate($request);

        if ($validator->fails()) {
            return redirect('databasethresholdprofiles/change/' . $databaseThresholdProfileModel->database_threshold_profile_id)
                ->withErrors($validator)
                ->withInput();
        }
        try {
            $profiles = $databaseThresholdProfileModel->getByName($databaseThresholdProfileModel->profile_name);

            if (count($profiles) > 0) {
                if ($profiles[0]->database_threshold_profile_id != $databaseThresholdProfileModel->database_threshold_profile_id) {
                    $validator->errors()->add('name', 'Profile already exists with that name');
                    return redirect('databasethresholdprofiles/change/' . $databaseThresholdProfileModel->database_threshold_profile_id)
                        ->withErrors($validator)
                        ->withInput();
                }

                for ($i = 0; $i < count($profiles[0]->params); $i++) {
                    $varname = "param_value_" . $profiles[0]->params[$i]->database_threshold_profile_param_id;
                    $paramValue = $request->input($varname);

                    $profiles[0]->params[$i]->param_value = $paramValue;
                };

                $databaseThresholdProfileModel->params = $profiles[0]->params;
            }
        } catch (\Exception $ex) {
            $validator->errors()->add('insert', $ex->getMessage());
            return redirect('databasethresholdprofiles/change/' . $databaseThresholdProfileModel->database_threshold_profile_id)
                ->withErrors($validator)
                ->withInput();
        }

        print_r($profiles[0]);

        try {
            $databaseThresholdProfileModel->updateRow();

            return redirect('databasethresholdprofiles')->with('message', 'Threshold profile was updated successfully');
        } catch (\Exception $ex) {
            $validator->errors()->add('insert', $ex->getMessage());
            return redirect('databasethresholdprofiles/change/' . $databaseThresholdProfileModel->database_threshold_profile_id)
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

        $databaseThresholdProfileModel = new DatabaseThresholdProfile();

        try {
            $profiles = $databaseThresholdProfileModel->get($profileId);
        } catch (\Exception $ex) {
            $validator->errors()->add('insert', $ex->getMessage());
            return redirect('databasethresholdprofiles')
                ->withErrors($validator)
                ->withInput();
        }

        $profile = $profiles[0];

        return view('databasethresholdprofiles.delete', ["profile" => $profile]);
    }

    public function remove(Request $request)
    {
        $params = $request->all();

        if (array_key_exists("cancelbtn", $params)) {
            return redirect('databasethresholdprofiles');
        }

        $profileId = $request->input("database_threshold_profile_id");

        $validator = Validator::make($request->all(), [
        ]);

        $databaseThresholdProfileModel = new DatabaseThresholdProfile();

        try {
            $databaseThresholdProfileModel->deleteRow($profileId);

            return redirect('databasethresholdprofiles')->with('message', 'Threshold profile was deleted successfully');
        } catch (\Exception $ex) {
            $validator->errors()->add('insert', $ex->getMessage());
            return redirect('databasethresholdprofiles/delete/' . $profileId)
                ->withErrors($validator)
                ->withInput();
        }
    }
}
