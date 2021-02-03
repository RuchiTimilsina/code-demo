<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use stdClass;
use DB;

class DeviceInfo extends Model
{
    use HasFactory;

    protected $table = 'device_info';

    public function getDeviceInfo($uID, $appID)
    {
        $result = $this->select('client_token')
                        ->where('uID', $uID)
                        ->where('appID', $appID)
                        ->first();

        return $result;
    }

    public function getuID($clientToken)
    {
        $result = $this->select('id', 'uID', 'os', 'appID')
                        ->where('client_token', $clientToken)
                        ->first();

        return $result;
    }

}
