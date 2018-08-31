<?php

namespace App\models;

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
        $sql = "insert into " . $this->table . " (profile_name, description, profile_type, warning_level, error_level) values ('" . $this->profile_name . "', '" .
        $this->description . "', '" . $this->profile_type . "', " . $this->warning_level . ", " . $this->error_level . ")";

        DB::insert($sql);
    }

    public function updateRow()
    {
        $sql = "update " . $this->table . " set profile_name = '" . $this->profile_name . "', description = '" . $this->description .
        "', profile_type = '" . $this->profile_type . "', warning_level = " . $this->warning_level . ", error_level = " . $this->error_level . " where profile_id = " . $this->profile_id;

        DB::update($sql);
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
            'profile_type' => 'required',
            'warning_level' => 'required|numeric',
            'error_level' => 'required|numeric',
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
                ->where(array('profile_id' => $profileId))
                ->get();
        }

        return $profiles;
    }

    public function getByName($profileName)
    {
        $profiles = DB::table($this->table)
            ->select('*')
            ->where(array('profile_name' => $profileName))
            ->get();

        return $profiles;
    }
}
