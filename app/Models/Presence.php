<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Presence extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['employee_id', 'leave_request_id', 'check_in', 'check_out', 'date', 'status'];

    protected function casts(): array
    {
        return ['date' => 'date', 'check_in' => 'datetime', 'check_out' => 'datetime'];
    }

    public function employee() { return $this->belongsTo(Employee::class)->withTrashed(); }
    public function leaveRequest() { return $this->belongsTo(LeaveRequest::class); }
}
