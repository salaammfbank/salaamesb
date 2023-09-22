<?php

namespace Noorfarooqy\EnterpriseServiceBus\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Noorfarooqy\LaravelOnfon\Traits\ErrorHandler;

class OnboardedCustomers extends Model
{
    use HasFactory;
    use ErrorHandler;

    protected $appends = [
        'onboard_status_value',
        // 'maker_name',
    ];
    protected $guarded = [
        'customer_id',
    ];
    protected $hidden = [
        'maker_info',
        'pin_number',
    ];

    protected $casts = [
        'approved_at' => 'datetime: Y-m-d H:i',
        'updated_at' => 'datetime: Y-m-d H:i',
        'created_at' => 'datetime: Y-m-d H:i',
        'is_active' => 'boolean',
        'is_locked' => 'boolean',
    ];

    protected function getMakerNameAttribute()
    {
        return $this->makerInfo?->name;
    }

    protected function makerInfo()
    {
        return $this->belongsTo(User::class, 'maker');
    }

    protected function checkerInfo()
    {
        return $this->belongsTo(User::class, 'maker');
    }

    public function getOnboardStatusValueAttribute()
    {
        switch ($this->onboard_status) {
            case 0:
                $status = 'Waiting approval';
                break;
            case 1:
                $status = 'Approved';
                break;
            case 2:
                $status = 'Rejected';
                break;
            default:
                $status = 'Unknown status';
                break;
        }
        return $status;
    }
}
