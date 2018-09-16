<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class DatabaseThresholdProfileParam extends Model
{
    protected $table = 'database_threshold_profile_params';

    public $database_threshold_profile_param_id = 0;
    public $database_threshold_profile_id = 0;
    public $param_name = "";
    public $param_num = 0;
    public $param_value = "";
    public $param_label = "";

    public function insertRow()
    {
        $sql = "insert into " . $this->table . " (database_threshold_profile_id, param_name, param_num, param_value, param_label) values (" .
        $this->database_threshold_profile_id . ", '" . $this->param_name . "', " . $this->param_num . ", '" .
        $this->param_value . "', '" . $this->param_label . "')";

        DB::insert($sql);
    }

    public function updateRow()
    {
        echo "In updatre params row<br>";

        $sql = "update " . $this->table . " set param_num = " . $this->param_num . ", param_name = '" .
        $this->param_name . "', param_value = '" . $this->param_value .
        "' where database_threshold_profile_param_id = " . $this->database_threshold_profile_param_id;

        DB::update($sql);
    }

    public function deleteRow($paramId)
    {
        DB::table($this->table)->where('database_threshold_profile_param_id', "=", $paramId)
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

    public function get($databaseThresholdProfileId = null)
    {
        if ($databaseThresholdProfileId === null) {
            $params = DB::table($this->table)
                ->select('*')
                ->orderBy('database_threshold_profile_id', 'asc')
                ->orderBy('param_num', 'asc')
                ->get();
        } else {
            $params = DB::table($this->table)
                ->select('*')
                ->where(array('database_threshold_profile_id' => $databaseThresholdProfileId))
                ->orderBy('param_num', 'asc')
                ->get();
        }

        return $params;
    }

    public function getByName($databaseThresholdProfileId, $paramName)
    {
        $param = DB::table($this->table)
            ->select('*')
            ->where(array('database_threshold_profile_id' => $databaseThresholdProfileId, 'param_name' => $paramName))
            ->get();

        return $param;
    }
}
