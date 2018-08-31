<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class ServerGroup extends Model
{
    protected $table = 'server_groups';

    public $server_group_id = 0;
    public $server_group_name = "";
    public $description = "";

    public function insertRow()
    {
        $sql = "insert into " . $this->table . " (server_group_name, description) values ('" . $this->server_group_name . "', '" .
        $this->description . "')";

        DB::insert($sql);
    }

    public function updateRow()
    {
        $sql = "update " . $this->table . " set server_group_name = '" . $this->server_group_name . "', description = " . $this->description .
        " where server_group_id = " . $this->server_group_id;

        DB::update($sql);
    }

    public function deleteRow($serverGroupId)
    {
        DB::table($this->table)->where('server_group_id', "=", $serverGroupId)
            ->delete();
    }

    public function validate(Request $request, $wantPassword = true)
    {
        $validator = $this->makeValidator($request, $wantPassword);

        return $validator;
    }

    private function makeValidator(Request $request, $wantPassword = true)
    {
        $rules = array('server_group_name' => 'required|max:60', 'description' => 'required|max:60');

        $validator = Validator::make($request->all(), $rules);

        return $validator;
    }

    public function get($serverGroupId = null)
    {
        if (!$serverGroupId) {
            $groups = DB::table($this->table)
                ->select('*')
                ->orderBy('server_groups.server_group_name', 'asc')
                ->get();
        } else {
            $groups = DB::table($this->table)
                ->select('*')
                ->where(array('server_group_id' => $serverGroupId))
                ->orderBy('server_group_name', 'asc')
                ->get();
        }

        return $groups;
    }

    public function getByName($serverGroupName)
    {
        $groups = DB::table($this->table)
            ->select('*')
            ->where(array('server_group_name' => $serverGroupName))
            ->get();

        return $groups;
    }
}
