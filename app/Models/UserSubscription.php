<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSubscription extends Model
{
    use HasFactory;

    protected $table = 'user_subscription';

    public function getSubscriptionDetail($dInfoId)
    {
        $result = $this->select('start_date', 'end_date', 'is_renewable', 'status')
                        ->where('device_info_id', $dInfoId)
                        ->orderBy('created_at', 'DESC')
                        ->first();

        return $result;
    }
}
