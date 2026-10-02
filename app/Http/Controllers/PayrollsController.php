<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Payroll;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class PayrollsController extends Controller
{
    public function index(Request $request)
    {
        $payrolls = Payroll::with('employee')
            ->when(!$request->user()->isHr(), fn ($q) => $q->where('employee_id', $request->user()->employee_id))
            ->latest('pay_date')->paginate(15);
        return view('payrolls.index', compact('payrolls'));
    }
    public function create()
    {
        return view('payrolls.create', ['employees' => Employee::where('status', 'active')->orderBy('fullname')->get()]);
    }
    public function store(Request $request)
    {
        $data = $request->validate($this->rules());
        $this->ensureUniquePeriod($data);
        Payroll::create($this->calculate($data));
        return redirect()->route('payrolls.index')->with('success', 'Payslip created.');
    }
    public function show(Request $request, Payroll $payroll)
    {
        abort_unless($request->user()->isHr() || $payroll->employee_id === $request->user()->employee_id, 403);
        $payroll->load('employee');
        return view('payrolls.show', compact('payroll'));
    }
    public function edit(Payroll $payroll)
    {
        return view('payrolls.edit', [
            'payroll' => $payroll,
            'employees' => Employee::orderBy('fullname')->get(),
        ]);
    }
    public function update(Request $request, Payroll $payroll)
    {
        $data = $request->validate($this->rules());
        $this->ensureUniquePeriod($data, $payroll);
        $payroll->update($this->calculate($data));
        return redirect()->route('payrolls.index')->with('success', 'Payslip updated.');
    }
    public function destroy(Payroll $payroll)
    {
        $payroll->delete();
        return redirect()->route('payrolls.index')->with('success', 'Payslip archived.');
    }
    private function rules(): array
    {
        return [
            'employee_id' => ['required', 'exists:employees,id'],
            'salary' => ['required', 'numeric', 'min:0', 'max:9999999999'],
            'bonuses' => ['nullable', 'numeric', 'min:0', 'max:9999999999'],
            'deductions' => ['nullable', 'numeric', 'min:0', 'max:9999999999'],
            'pay_date' => ['required', 'date'],
        ];
    }
    private function ensureUniquePeriod(array $data, ?Payroll $payroll = null): void
    {
        $date = Carbon::parse($data['pay_date']);
        $exists = Payroll::where('employee_id', $data['employee_id'])
            ->whereYear('pay_date', $date->year)->whereMonth('pay_date', $date->month)
            ->when($payroll, fn ($q) => $q->where('id', '!=', $payroll->id))->exists();
        if ($exists) throw ValidationException::withMessages(['pay_date' => 'A payslip already exists for this employee and month.']);
    }
    private function calculate(array $data): array
    {
        $data['bonuses'] = round((float)($data['bonuses'] ?? 0), 2);
        $data['deductions'] = round((float)($data['deductions'] ?? 0), 2);
        $data['salary'] = round((float)$data['salary'], 2);
        $data['net_salary'] = round($data['salary'] + $data['bonuses'] - $data['deductions'], 2);
        if ($data['net_salary'] < 0) {
            throw ValidationException::withMessages(['deductions' => 'Deductions cannot exceed salary plus bonuses.']);
        }
        return $data;
    }
}
