<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class DatabaseConnection extends Model
{
    protected $table = 'database_connections';

    public $server_group_id = 0;
    public $username = "";
    public $password = "";
    public $port = "";

    public function insertRow()
    {
        $secretKey = env('DBCONNECTION_PROFILE_PASSWORD_SECRET_KEY');

        if ($this->port == "") {
            $this->port = 3306;
        }

        $sql = "insert into " . $this->table . " (server_group_id, username, password, port) values (" . $this->server_group_id . ", '" .
        $this->username . "', DES_ENCRYPT('" . $this->password . "', '$secretKey'), " . $this->port . ")";

        try {
            DB::insert($sql);
        } catch (\Exception $ex) {
            throw new \Exception(str_replace($this->password, 'XXXX', str_replace($secretKey, 'XXXXX', $ex->getMessage())));
        }

    }

    public function updateRow()
    {
        $secretKey = env('DBCONNECTION_PROFILE_PASSWORD_SECRET_KEY');

        $sql = "update " . $this->table . " set username = '" . $this->username . "', port = " . $this->port;

        if ($this->password != "") {
            $sql .= ", password = DES_ENCRYPT('" . $this->password . "', '$secretKey')";
        }

        $sql .= " WHERE server_group_id = " . $this->server_group_id;

        try {
            DB::update($sql);
        } catch (\Exception $ex) {
            throw new \Exception(str_replace($this->password, 'XXXX', str_replace($secretKey, 'XXXXX', $ex->getMessage())));
        }
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
        $rules = array('username' => 'required|max:60');

        if ($wantPassword) {
            $rules['password'] = 'required|max:60';
        }

        $validator = Validator::make($request->all(), $rules);

        return $validator;
    }

    public function get($serverGroupId = null)
    {
        if (!$serverGroupId) {
            $profiles = DB::table('database_connections')
                ->select('database_connections.server_group_id', 'database_connections.username', 'database_connections.port', 'server_groups.server_group_name')
                ->join('server_groups', 'database_connections.server_group_id', '=', 'server_groups.server_group_id')
                ->orderBy('server_groups.server_group_name', 'asc')
                ->get();
        } else {
            $profiles = DB::table('database_connections')
                ->select('database_connections.server_group_id', 'database_connections.username', 'database_connections.port', 'server_groups.server_group_name')
                ->join('server_groups', 'database_connections.server_group_id', '=', 'server_groups.server_group_id')
                ->where(array('database_connections.server_group_id' => $serverGroupId))
                ->orderBy('server_groups.server_group_name', 'asc')
                ->get();
        }

        return $profiles;
    }
}
