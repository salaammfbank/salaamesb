<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerCifs extends Model
{
    use HasFactory;

    protected $appends = [
        'onboard_status_value', 'maker_name', 'customer_name',
    ];
    protected $guarded = [
    ];

    protected $casts = [
        'approved_at' => 'datetime: Y-m-d H:i',
        'updated_at' => 'datetime: Y-m-d H:i',
        'created_at' => 'datetime: Y-m-d H:i',
        'is_active' => 'boolean',
        'is_locked' => 'boolean',
    ];
    protected $hidden = [
        'maker_info',
        'pin_number',
    ];

    protected function getMakerNameAttribute()
    {
        return $this->makerInfo?->name;
    }

    protected function getCustomerNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
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

    public function accounts()
    {
        return $this->hasMany(OnboardedCustomers::class, 'customer_cif_id');
    }
}
