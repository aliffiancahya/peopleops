<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payroll extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'payroll';
    protected $fillable = ['employee_id', 'salary', 'bonuses', 'deductions', 'net_salary', 'pay_date'];

    protected function casts(): array
    {
        return ['pay_date' => 'date', 'salary' => 'decimal:2', 'bonuses' => 'decimal:2',
            'deductions' => 'decimal:2', 'net_salary' => 'decimal:2'];
    }

    public function employee() { return $this->belongsTo(Employee::class)->withTrashed(); }
}
