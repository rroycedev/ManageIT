<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class Server extends Model
{
    protected $table = 'servers';

    public $server_id = 0;
    public $server_name = "";
    public $server_type = "";
    public $environment = "";
    public $hostname = "";
    public $server_group_id = "";
    public $server_threshold_profile_id = 0;
    public $database_threshold_profile_id = 0;
    public $status = "";

    public function insertRow()
    {
        $sql = "insert into " . $this->table . " (server_name, server_type, environment, hostname, server_group_id, status, server_threshold_profile_id, database_threshold_profile_id) values ('" .
        $this->server_name . "', '" .
        $this->server_type . "', '" . $this->environment . "', '" . $this->hostname . "', " . $this->server_group_id . ", '" . $this->status . "', " .
        $this->server_threshold_profile_id . ", " . $this->database_threshold_profile_id . ")";

        DB::insert($sql);
    }

    public function updateRow()
    {
        $sql = "update " . $this->table . " set server_name = '" . $this->server_name . "', server_type = '" . $this->server_type . "', environment = '" .
        $this->environment . "', hostname = '" . $this->hostname . "', server_group_id = " . $this->server_group_id . ", status = '" . $this->status .
        "', server_threshold_profile_id = " . $this->server_threshold_profile_id . ", database_threshold_profile_id = " . $this->database_threshold_profile_id . " where server_id = " .
        $this->server_id;

        DB::update($sql);
    }

    public function deleteRow($serverId)
    {
        DB::table($this->table)->where('server_id', "=", $serverId)
            ->delete();
    }

    public function validate(Request $request, $wantPassword = true)
    {
        $validator = $this->makeValidator($request, $wantPassword);

        return $validator;
    }

    private function makeValidator(Request $request, $wantPassword = true)
    {
        $rules = array('server_name' => 'required|max:60', 'server_type' => 'required', 'environment' => 'required', 'hostname' => 'required', 'server_group_id' => 'required',
            'status' => 'required', 'server_threshold_profile_id' => 'required');

        $validator = Validator::make($request->all(), $rules);

        return $validator;
    }

    public function get($serverId = null)
    {
        if (!$serverId) {
            $servers = DB::table($this->table)
                ->select('servers.server_id', 'servers.server_name', 'servers.server_type', 'servers.environment', 'servers.hostname',
                    'servers.server_group_id', 'servers.status', 'server_groups.server_group_name', 'servers.server_threshold_profile_id', 'server_threshold_profiles.profile_name',
                    'servers.database_threshold_profile_id', 'database_threshold_profiles.profile_name')
                ->join('server_groups', 'servers.server_group_id', '=', 'server_groups.server_group_id')
                ->join('server_threshold_profiles', 'servers.server_threshold_profile_id', '=', 'server_threshold_profiles.server_threshold_profile_id')
                ->join('database_threshold_profiles', 'servers.database_threshold_profile_id', '=', 'database_threshold_profiles.database_threshold_profile_id')
                ->orderBy('servers.server_name', 'asc')
                ->get();
        } else {
            $servers = DB::table($this->table)
                ->select('servers.server_id', 'servers.server_name', 'servers.server_type', 'servers.environment', 'servers.hostname',
                    'servers.server_group_id', 'servers.status', 'server_groups.server_group_name', 'servers.server_threshold_profile_id', 'server_threshold_profiles.profile_name',
                    'servers.database_threshold_profile_id', 'database_threshold_profiles.profile_name')
                ->join('server_groups', 'servers.server_group_id', '=', 'server_groups.server_group_id')
                ->join('server_threshold_profiles', 'servers.server_threshold_profile_id', '=', 'server_threshold_profiles.server_threshold_profile_id')
                ->join('database_threshold_profiles', 'servers.database_threshold_profile_id', '=', 'database_threshold_profiles.database_threshold_profile_id')
                ->where(array('servers.server_id' => $serverId))
                ->get();
        }

        return $servers;
    }

    public function getByName($serverName)
    {
        $servers = DB::table($this->table)
            ->select('*')
            ->where(array('server_name' => $serverName))
            ->get();

        return $servers;
    }

    public function getByHostname($hostname)
    {
        $servers = DB::table($this->table)
            ->select('*')
            ->where(array('hostname' => $hostname))
            ->get();

        return $servers;
    }
}
