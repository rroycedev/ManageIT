<?php

namespace App\models;

use App\models\ServerThresholdProfileParam;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class ServerThresholdProfile extends Model
{
    protected $table = 'server_threshold_profiles';

    public $profile_id = 0;
    public $profile_name = "";
    public $description = "";
    public $profile_type = "";
    public $warning_level = 0;
    public $error_level = 0;

    public function insertRow()
    {
        DB::beginTransaction();

        $sql = "insert into " . $this->table . " (profile_name, description) values ('" . $this->profile_name . "', '" .
        $this->description . "')";

        DB::insert($sql);

        $id = DB::getPdo()->lastInsertId();

        foreach ($this->params as $param) {
            $paramsModel = new ServerThresholdProfileParam();

            $paramsModel->server_threshold_profile_id = $id;
            $paramsModel->param_name = $param->param_name;
            $paramsModel->param_num = $param->param_num;
            $paramsModel->param_value = $param->param_value;
            $paramsModel->param_label = $param->param_label;

            $paramsModel->insertRow();
        }

        DB::commit();
    }

    public function updateRow()
    {
        DB::beginTransaction();

        $sql = "update " . $this->table . " set profile_name = '" . $this->profile_name . "', description = '" . $this->description .
        "' where server_threshold_profile_id = " . $this->server_threshold_profile_id;

        DB::update($sql);

        foreach ($this->params as $param) {
            $paramsModel = new ServerThresholdProfileParam();

            $paramsModel->server_threshold_profile_param_id = $param->server_threshold_profile_param_id;
            $paramsModel->server_threshold_profile_id = $param->server_threshold_profile_id;
            $paramsModel->param_name = $param->param_name;
            $paramsModel->param_num = $param->param_num;
            $paramsModel->param_value = $param->param_value;

            $paramsModel->updateRow();
        }

        DB::commit();
    }

    public function deleteRow($profileId)
    {
        DB::table($this->table)->where('profile_id', "=", $profileId)
            ->delete();
    }

    public function validate(Request $request)
    {
        $validator = $this->makeValidator($request);

        return $validator;
    }

    private function makeValidator(Request $request)
    {
        $rules = [
            'profile_name' => 'required|max:60',
            'description' => 'required|max:60',

        ];

        $validator = Validator::make($request->all(), $rules);

        return $validator;
    }

    public function get($profileId = null)
    {
        if (!$profileId) {
            $profiles = DB::table($this->table)
                ->select('*')
                ->orderBy('profile_name', 'asc')
                ->get();

        } else {
            $profiles = DB::table($this->table)
                ->select('*')
                ->where(array('server_threshold_profile_id' => $profileId))
                ->get();

            $paramsModel = new ServerThresholdProfileParam();

            for ($i = 0; $i < count($profiles); $i++) {
                try {
                    $params = $paramsModel->get($profileId);
                } catch (\Exception $ex) {
                    throw new Exception("Error getting params for profile $profileId");
                }

                $profiles[$i]->params = $params;
            }
        }

        return $profiles;
    }

    public function getByName($profileName)
    {
        $profiles = DB::table($this->table)
            ->select('*')
            ->where(array('profile_name' => $profileName))
            ->get();

        $paramsModel = new ServerThresholdProfileParam();

        for ($i = 0; $i < count($profiles); $i++) {
            try {
                $params = $paramsModel->get($profiles[$i]->server_threshold_profile_id);
            } catch (\Exception $ex) {
                throw new Exception("Error getting params for profile $profileId");
            }

            $profiles[$i]->params = $params;
        }
        return $profiles;
    }
}
