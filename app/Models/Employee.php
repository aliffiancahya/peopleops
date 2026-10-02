<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'fullname', 'email', 'phone_number', 'address', 'birth_date', 'hire_date',
        'department_id', 'role_id', 'status', 'salary',
    ];

    protected function casts(): array
    {
        return ['birth_date' => 'date', 'hire_date' => 'date', 'salary' => 'decimal:2'];
    }

    public function department() { return $this->belongsTo(Department::class); }
    public function role() { return $this->belongsTo(Role::class); }
    public function user() { return $this->hasOne(User::class); }
    public function tasks() { return $this->hasMany(Task::class, 'assigned_to'); }
    public function presences() { return $this->hasMany(Presence::class); }
    public function leaveRequests() { return $this->hasMany(LeaveRequest::class); }
}
