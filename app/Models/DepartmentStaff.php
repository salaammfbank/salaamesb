<?php

namespace Noorfarooqy\SalaamEsb\Models;

use Drongotech\ResponseParser\Traits\ErrorParser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Noorfarooqy\LaravelOnfon\Traits\ErrorHandler;

class DepartmentStaff extends Model
{
    use HasFactory;
    use ErrorHandler;

    protected $appends = ['staff_name', 'staff_email', 'department_name'];

    protected $guarded = ['dep_staff_id'];

    public function updateOrCreateStaffDepartment($data)
    {
        try {
            $department = $this->updateOrCreate(['id' => $data['dep_staff_id'] ?? 0], $data);
            return $department;
        } catch (\Throwable$th) {
            $this->setError(env('APP_DEBUG') ? $th->getMessage() : $this->update_or_create_error);
            return false;
        }
    }
    public function getDepartmentNameAttribute()
    {
        return $this->departmentInfo?->department_name;
    }

    public function getStaffNameAttribute()
    {
        return $this->departmentStaff?->name;
    }

    public function getStaffEmailAttribute()
    {
        return $this->departmentStaff?->email;
    }

    public function departmentStaff()
    {
        return $this->belongsTo(User::class, 'staff');
    }
    public function departmentInfo()
    {
        return $this->belongsTo(Departments::class, 'department');
    }
}
