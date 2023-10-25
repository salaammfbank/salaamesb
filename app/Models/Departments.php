<?php

namespace Noorfarooqy\SalaamEsb\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Noorfarooqy\LaravelOnfon\Traits\ErrorHandler;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Departments extends Model
{
    use HasFactory;
    use ErrorHandler;
    use LogsActivity;

    protected $guarded = [];
    private $update_or_create_error = 'Oops! Cannot create or update the department. Please contact system admin for assistance';
    public function updateOrCreateDepartment($data)
    {
        try {
            $department = $this->updateOrCreate(['id' => $data['department_id'] ?? 0], $data);
            return $department;
        } catch (\Throwable $th) {
            $this->setError(env('APP_DEBUG') ? $th->getMessage() : $this->update_or_create_error);
            return false;
        }
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
    }

    public function hod()
    {
        return $this->belongsTo(User::class, 'department_hod');
    }

    public function departmentStaff()
    {
        return $this->hasMany(DepartmentStaff::class, 'department');
    }

    public static function booted()
    {
        static::creating(function ($user) {
            $user->created_by = Auth::user()->id;
        });
    }
}
