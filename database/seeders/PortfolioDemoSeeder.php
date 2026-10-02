<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\Payroll;
use App\Models\Presence;
use App\Models\Role;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PortfolioDemoSeeder extends Seeder
{
    public function run(): void
    {
        if (!app()->environment(['local', 'testing'])) {
            $this->command?->warn('Demo seeding is restricted to local and testing environments.');
            return;
        }
        DB::transaction(function () {
            $departments = [];
            foreach (['People & Culture', 'Engineering', 'Sales', 'Design'] as $name) {
                $departments[$name] = Department::firstOrCreate(['name' => $name], [
                    'description' => "Demo {$name} department", 'status' => 'active',
                ]);
            }
            $roles = [];
            foreach (['HR', 'Developer', 'Salesperson', 'Designer'] as $title) {
                $roles[$title] = Role::firstOrCreate(['title' => $title], [
                    'description' => $title === 'HR' ? 'Manages people operations' : "Demo {$title} employee",
                ]);
            }
            $fixtures = [
                ['Maya Pratama', 'hr@demo.test', 'People & Culture', 'HR', 13000000],
                ['Alex Wijaya', 'alex@demo.test', 'Engineering', 'Developer', 10500000],
                ['Nadia Putri', 'nadia@demo.test', 'Engineering', 'Developer', 11000000],
                ['Kevin Santoso', 'kevin@demo.test', 'Engineering', 'Developer', 9700000],
                ['Rina Hartono', 'rina@demo.test', 'Sales', 'Salesperson', 8900000],
                ['Dimas Nugraha', 'dimas@demo.test', 'Sales', 'Salesperson', 9300000],
                ['Tari Maharani', 'tari@demo.test', 'Design', 'Designer', 10200000],
                ['Bagas Saputra', 'bagas@demo.test', 'People & Culture', 'HR', 11600000],
            ];
            $employees = [];
            foreach ($fixtures as $i => [$name, $email, $department, $role, $salary]) {
                $employee = Employee::firstOrCreate(['email' => $email], [
                    'fullname' => $name, 'phone_number' => '0812'.str_pad((string)($i + 1), 8, '0', STR_PAD_LEFT),
                    'address' => 'Jakarta, Indonesia', 'birth_date' => Carbon::now()->subYears(24 + $i)->subMonths(3)->toDateString(),
                    'hire_date' => Carbon::now()->subMonths(18 - $i)->toDateString(),
                    'department_id' => $departments[$department]->id, 'role_id' => $roles[$role]->id,
                    'status' => 'active', 'salary' => $salary,
                ]);
                $employees[] = $employee;
            }
            // Demo-only credentials, not suitable for public internet deployment.
            $password = env('DEMO_PASSWORD', 'Password123!');
            foreach ([$employees[0], $employees[1]] as $employee) {
                $user = User::firstOrCreate(['email' => $employee->email], [
                    'name' => $employee->fullname, 'employee_id' => $employee->id, 'password' => $password,
                ]);
                if (!$user->email_verified_at) {
                    $user->forceFill(['email_verified_at' => now()])->save();
                }
            }
            foreach ($employees as $i => $employee) {
                for ($n = 5; $n >= 0; $n--) {
                    $month = Carbon::now()->startOfMonth()->subMonths($n);
                    $payDate = $month->isSameMonth(Carbon::now()) ? Carbon::now()->toDateString() : $month->endOfMonth()->toDateString();
                    $bonus = $i % 3 === 0 ? 350000 : 150000;
                    $deduction = $i % 4 === 0 ? 95000 : 45000;
                    Payroll::firstOrCreate(['employee_id' => $employee->id, 'pay_date' => $payDate], [
                        'salary' => $employee->salary, 'bonuses' => $bonus, 'deductions' => $deduction,
                        'net_salary' => (float) $employee->salary + $bonus - $deduction,
                    ]);
                    foreach ([3, 6, 9, 12, 15, 18, 21] as $day) {
                        if ($month->copy()->day($day)->isFuture()) continue;
                        $date = $month->copy()->day($day)->toDateString();
                        Presence::firstOrCreate(['employee_id' => $employee->id, 'date' => $date], [
                            'check_in' => $month->copy()->day($day)->setTime(9, $i % 3 * 5),
                            'check_out' => $month->copy()->day($day)->setTime(17, 0), 'status' => 'present',
                        ]);
                    }
                }
            }
            $taskFixtures = [
                ['Improve onboarding checklist', 0, -2, 'on progress'],
                ['Build employee directory API', 1, 3, 'on progress'],
                ['Review leave approval flow', 0, 5, 'pending'],
                ['Update internal design system', 6, 8, 'pending'],
                ['Prepare Q3 people report', 7, -1, 'done'],
                ['Automate monthly attendance review', 2, 12, 'pending'],
                ['Refine sales handoff process', 4, 6, 'done'],
                ['Add payroll reconciliation tests', 1, 9, 'pending'],
            ];
            foreach ($taskFixtures as [$title, $assignee, $days, $status]) {
                Task::firstOrCreate(['title' => $title], [
                    'description' => 'Sample task for exploring the demo workspace.',
                    'assigned_to' => $employees[$assignee]->id, 'due_date' => Carbon::now()->addDays($days)->toDateString(),
                    'status' => $status,
                ]);
            }
            LeaveRequest::firstOrCreate([
                'employee_id' => $employees[1]->id, 'start_date' => Carbon::now()->addDays(9)->toDateString(),
            ], [
                'leave_type' => 'Annual leave', 'end_date' => Carbon::now()->addDays(11)->toDateString(), 'status' => 'pending',
            ]);
            LeaveRequest::firstOrCreate([
                'employee_id' => $employees[4]->id, 'start_date' => Carbon::now()->addDays(15)->toDateString(),
            ], [
                'leave_type' => 'Family leave', 'end_date' => Carbon::now()->addDays(16)->toDateString(), 'status' => 'pending',
            ]);
            $previousMonth = Carbon::now()->startOfMonth()->subMonth();
            $leave = LeaveRequest::firstOrCreate([
                'employee_id' => $employees[2]->id, 'start_date' => $previousMonth->copy()->day(25)->toDateString(),
            ], [
                'leave_type' => 'Annual leave', 'end_date' => $previousMonth->copy()->day(26)->toDateString(),
                'status' => 'confirmed',
            ]);
            foreach ([25, 26] as $day) {
                Presence::firstOrCreate([
                    'employee_id' => $employees[2]->id, 'date' => $previousMonth->copy()->day($day)->toDateString(),
                ], ['leave_request_id' => $leave->id, 'status' => 'leave']);
            }
        });
    }
}
