<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class ServerThresholdProfileParam extends Model
{
    protected $table = 'server_threshold_profile_params';

    public $server_threshold_profile_param_id = 0;
    public $server_threshold_profile_id = 0;
    public $param_name = "";
    public $param_num = 0;
    public $param_value = "";
    public $param_label = "";

    public function insertRow()
    {
        $sql = "insert into " . $this->table . " (server_threshold_profile_id, param_name, param_num, param_value, param_label) values (" .
        $this->server_threshold_profile_id . ", '" . $this->param_name . "', " . $this->param_num . ", '" .
        $this->param_value . "', '" . $this->param_label . "')";

        DB::insert($sql);
    }

    public function updateRow()
    {
        echo "In updatre params row<br>";

        $sql = "update " . $this->table . " set param_num = " . $this->param_num . ", param_name = '" .
        $this->param_name . "', param_value = '" . $this->param_value .
        "' where server_threshold_profile_param_id = " . $this->server_threshold_profile_param_id;

        DB::update($sql);
    }

    public function deleteRow($paramId)
    {
        DB::table($this->table)->where('server_threshold_profile_param_id', "=", $paramId)
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
            'param_name' => 'required|max:60',
            'description' => 'required|max:60',
            'profile_type' => 'required',
            'warning_level' => 'required|numeric',
            'error_level' => 'required|numeric',
        ];

        $validator = Validator::make($request->all(), $rules);

        return $validator;
    }

    public function get($serverThresholdProfileId = null)
    {
        if ($serverThresholdProfileId === null) {
            $params = DB::table($this->table)
                ->select('*')
                ->orderBy('server_threshold_profile_id', 'asc')
                ->orderBy('param_num', 'asc')
                ->get();
        } else {
            $params = DB::table($this->table)
                ->select('*')
                ->where(array('server_threshold_profile_id' => $serverThresholdProfileId))
                ->orderBy('param_num', 'asc')
                ->get();
        }

        return $params;
    }

    public function getByName($serverThresholdProfileId, $paramName)
    {
        $param = DB::table($this->table)
            ->select('*')
            ->where(array('server_threshold_profile_id' => $serverThresholdProfileId, 'param_name' => $paramName))
            ->get();

        return $param;
    }
}
