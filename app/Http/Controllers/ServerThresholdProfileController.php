<?php

namespace App\Http\Controllers;

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

        $serverThresholdProfileModel->profile_id = $request->input('profile_id');
        $serverThresholdProfileModel->profile_name = $request->input('profile_name');
        $serverThresholdProfileModel->description = $request->input('description');
        $serverThresholdProfileModel->profile_type = $request->input('profile_type');
        $serverThresholdProfileModel->warning_level = $request->input('warning_level');
        $serverThresholdProfileModel->error_level = $request->input('error_level');

        return $serverThresholdProfileModel;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function add()
    {
        return view('serverthresholdprofiles.add');
    }
    public function insert(Request $request)
    {
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
            $validator->errors()->add('insert', $ex->getMessage());
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
            return redirect('serverthresholdprofiles/change/' . $serverThresholdProfileModel->profile_id)
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $profiles = $serverThresholdProfileModel->getByName($serverThresholdProfileModel->profile_name);

            if (count($profiles) > 0) {
                if ($profiles[0]->profile_id != $serverThresholdProfileModel->profile_id) {
                    $validator->errors()->add('name', 'Profile already exists with that name');
                    return redirect('serverthresholdprofiles/add')
                        ->withErrors($validator)
                        ->withInput();
                }
            }
        } catch (\Exception $ex) {
            $validator->errors()->add('insert', $ex->getMessage());
            return redirect('serverthresholdprofiles/add')
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $serverThresholdProfileModel->updateRow();

            return redirect('serverthresholdprofiles')->with('message', 'Threshold profile was updated successfully');
        } catch (\Exception $ex) {
            $validator->errors()->add('insert', $ex->getMessage());
            return redirect('serverthresholdprofiles/change/' . $serverThresholdProfileModel->profile_id)
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
