<?php

namespace App\Http\Controllers;

use App\models\ServerThresholdParamNames;
use App\models\ServerThresholdProfile;
use Illuminate\Http\Request;
use Validator;

class ServerThresholdProfileController extends Controller
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
        $serverThresholdProfileModel = new ServerThresholdProfile();

        try {
            $profiles = $serverThresholdProfileModel->get();
        } catch (\Exception $ex) {
            $validator->errors()->add('insert', $ex->getMessage());
            return redirect('serverthresholdprofiles')
                ->withErrors($validator)
                ->withInput();
        }

        return view('serverthresholdprofiles', ["profiles" => $profiles]);
    }

    private function requestToModel(Request $request)
    {
        $serverThresholdProfileModel = new ServerThresholdProfile();

        $serverThresholdProfileModel->server_threshold_profile_id = $request->input('server_threshold_profile_id');
        $serverThresholdProfileModel->profile_name = $request->input('profile_name');
        $serverThresholdProfileModel->description = $request->input('description');

        return $serverThresholdProfileModel;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function add()
    {
        $paramNamesModel = new ServerThresholdParamNames();

        $validator = Validator::make([], [
        ]);

        try {
            $paramNames = $paramNamesModel->get();

        } catch (\Exception $ex) {
            $validator->errors()->add('name', 'Error retrieving parameter names: ' . $ex->getMessage());
            return redirect('serverthresholdprofiles')
                ->withErrors($validator)
                ->withInput();
        }
        return view('serverthresholdprofiles.add', ["param_names" => $paramNames]);
    }
    public function insert(Request $request)
    {
        $paramNamesModel = new ServerThresholdParamNames();

        $validator = Validator::make([], [
        ]);

        try {
            $paramNames = $paramNamesModel->get();

        } catch (\Exception $ex) {
            $validator->errors()->add('name', 'Error retrieving parameter names: ' . $ex->getMessage());
            return redirect('serverthresholdprofiles')
                ->withErrors($validator)
                ->withInput();
        }
        $params = $request->all();

        if (array_key_exists("donebtn", $params)) {
            return redirect('serverthresholdprofiles');
        }

        $serverThresholdProfileModel = $this->requestToModel($request);

        $validator = $serverThresholdProfileModel->validate($request);

        if ($validator->fails()) {
            return redirect('serverthresholdprofiles/add')
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $profiles = $serverThresholdProfileModel->getByName($serverThresholdProfileModel->profile_name);

            if (count($profiles) > 0) {
                $validator->errors()->add('name', 'Profile already exists with that name');
                return redirect('serverthresholdprofiles/add')
                    ->withErrors($validator)
                    ->withInput();
            }
        } catch (\Exception $ex) {
            $validator->errors()->add('insert', $ex->getMessage());
            return redirect('serverthresholdprofiles/add')
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

        $serverThresholdProfileModel->params = $params;

        try {
            $serverThresholdProfileModel->insertRow();

            return redirect()->back()->with('message', 'Server threshold profile was added successfully');
        } catch (\Exception $ex) {
            $validator->errors()->add('insert', $ex->getMessage());
            return redirect('serverthresholdprofiles/add')
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

        $serverThresholdProfileModel = new ServerThresholdProfile();

        try {
            $profiles = $serverThresholdProfileModel->get($profileId);
        } catch (\Exception $ex) {
            $validator->errors()->add('insert', "Error getting profile id $profileId : " . $ex->getMessage());
            return redirect('serverthresholdprofiles')
                ->withErrors($validator)
                ->withInput();
        }

        $profile = $profiles[0];

        return view('serverthresholdprofiles.change', ["profile" => $profile]);
    }

    public function update(Request $request)
    {
        $params = $request->all();

        if (array_key_exists("cancelbtn", $params)) {
            return redirect('serverthresholdprofiles');
        }

        $serverThresholdProfileModel = $this->requestToModel($request);

        $validator = $serverThresholdProfileModel->validate($request);

        if ($validator->fails()) {
            return redirect('serverthresholdprofiles/change/' . $serverThresholdProfileModel->server_threshold_profile_id)
                ->withErrors($validator)
                ->withInput();
        }
        try {
            $profiles = $serverThresholdProfileModel->getByName($serverThresholdProfileModel->profile_name);

            if (count($profiles) > 0) {
                if ($profiles[0]->server_threshold_profile_id != $serverThresholdProfileModel->server_threshold_profile_id) {
                    $validator->errors()->add('name', 'Profile already exists with that name');
                    return redirect('serverthresholdprofiles/change/' . $serverThresholdProfileModel->server_threshold_profile_id)
                        ->withErrors($validator)
                        ->withInput();
                }

                for ($i = 0; $i < count($profiles[0]->params); $i++) {
                    $varname = "param_value_" . $profiles[0]->params[$i]->server_threshold_profile_param_id;
                    $paramValue = $request->input($varname);

                    $profiles[0]->params[$i]->param_value = $paramValue;
                };

                $serverThresholdProfileModel->params = $profiles[0]->params;
            }
        } catch (\Exception $ex) {
            $validator->errors()->add('insert', $ex->getMessage());
            return redirect('serverthresholdprofiles/change/' . $serverThresholdProfileModel->server_threshold_profile_id)
                ->withErrors($validator)
                ->withInput();
        }

        print_r($profiles[0]);

        try {
            $serverThresholdProfileModel->updateRow();

            return redirect('serverthresholdprofiles')->with('message', 'Threshold profile was updated successfully');
        } catch (\Exception $ex) {
            $validator->errors()->add('insert', $ex->getMessage());
            return redirect('serverthresholdprofiles/change/' . $serverThresholdProfileModel->server_threshold_profile_id)
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

        $serverThresholdProfileModel = new ServerThresholdProfile();

        try {
            $profiles = $serverThresholdProfileModel->get($profileId);
        } catch (\Exception $ex) {
            $validator->errors()->add('insert', $ex->getMessage());
            return redirect('serverthresholdprofiles')
                ->withErrors($validator)
                ->withInput();
        }

        $profile = $profiles[0];

        return view('serverthresholdprofiles.delete', ["profile" => $profile]);
    }

    public function remove(Request $request)
    {
        $params = $request->all();

        if (array_key_exists("cancelbtn", $params)) {
            return redirect('serverthresholdprofiles');
        }

        $profileId = $request->input("profile_id");

        $validator = Validator::make($request->all(), [
        ]);

        $serverThresholdProfileModel = new ServerThresholdProfile();

        try {
            $serverThresholdProfileModel->deleteRow($profileId);

            return redirect('serverthresholdprofiles')->with('message', 'Threshold profile was deleted successfully');
        } catch (\Exception $ex) {
            $validator->errors()->add('insert', $ex->getMessage());
            return redirect('serverthresholdprofiles/delete')
                ->withErrors($validator)
                ->withInput();
        }
    }
}
