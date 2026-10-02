<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\Payroll;
use App\Models\Presence;
use App\Models\Task;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    private function overview(Request $request): array
    {
        $isHr = $request->user()->isHr();
        $employeeId = $request->user()->employee_id;
        $scope = fn ($query, $column = 'employee_id') => $query->when(!$isHr, fn ($q) => $q->where($column, $employeeId));
        $months = collect(range(5, 0))->map(fn ($n) => Carbon::now()->startOfMonth()->subMonths($n));
        $start = $months->first()->toDateString();

        $presences = $scope(Presence::query())->where('status', 'present')->whereDate('date', '>=', $start)
            ->get(['date'])->countBy(fn ($presence) => $presence->date->format('Y-m'));
        $payrolls = $scope(Payroll::query())->whereDate('pay_date', '>=', $start)->get()
            ->groupBy(fn ($payroll) => $payroll->pay_date->format('Y-m'))
            ->map(fn ($items) => round($items->sum('net_salary'), 2));

        return [
            'isHr' => $isHr,
            'employeeCount' => $isHr ? Employee::where('status', 'active')->count() : null,
            'departmentCount' => $isHr ? Department::where('status', 'active')->count() : null,
            'presenceCount' => $scope(Presence::query())->where('status', 'present')->whereMonth('date', now()->month)
                ->whereYear('date', now()->year)->count(),
            'pendingLeaves' => $scope(LeaveRequest::query())->where('status', 'pending')->count(),
            'openTasks' => $scope(Task::query(), 'assigned_to')->where('status', '!=', 'done')->count(),
            'latestTasks' => $scope(Task::with('employee'), 'assigned_to')->latest()->limit(6)->get(),
            'months' => $months->map(fn ($month) => $month->format('M Y'))->all(),
            'presenceSeries' => $months->map(fn ($month) => (int) $presences->get($month->format('Y-m'), 0))->all(),
            'payrollSeries' => $months->map(fn ($month) => (float) $payrolls->get($month->format('Y-m'), 0))->all(),
        ];
    }

    public function index(Request $request)
    {
        return view('dashboard.index', $this->overview($request));
    }

    public function presence(Request $request)
    {
        $overview = $this->overview($request);
        return response()->json(['labels' => $overview['months'], 'values' => $overview['presenceSeries']]);
    }
}
