@php($payroll = $payroll ?? null)
<form method="POST" action="{{ $payroll ? route('payrolls.update', $payroll) : route('payrolls.store') }}">
    @csrf @if ($payroll)
        @method('PUT')
    @endif
    <div class="row g-4">
        <div class="col-md-7"><label for="employee_id" class="form-label">Employee *</label><select name="employee_id"
                id="employee_id" class="form-select" required>
                <option value="">Choose employee</option>
                @foreach ($employees as $employee)
                    <option value="{{ $employee->id }}" data-salary="{{ $employee->salary }}"
                        @selected(old('employee_id', $payroll?->employee_id) == $employee->id)>{{ $employee->fullname }}</option>
                @endforeach
            </select></div>
        <div class="col-md-5"><label for="pay_date" class="form-label">Pay date *</label><input type="date"
                name="pay_date" id="pay_date" class="form-control"
                value="{{ old('pay_date', $payroll?->pay_date?->format('Y-m-d') ?? now()->toDateString()) }}" required>
            <p class="form-hint">One payslip per employee per calendar month.</p>
        </div>
        <div class="col-md-4"><label for="salary" class="form-label">Base salary (IDR) *</label><input type="number"
                min="0" step="0.01" max="9999999999" name="salary" id="salary" class="form-control"
                value="{{ old('salary', $payroll?->salary) }}" required></div>
        <div class="col-md-4"><label for="bonuses" class="form-label">Bonuses (IDR)</label><input type="number"
                min="0" step="0.01" max="9999999999" name="bonuses" id="bonuses" class="form-control"
                value="{{ old('bonuses', $payroll?->bonuses ?? 0) }}"></div>
        <div class="col-md-4"><label for="deductions" class="form-label">Deductions (IDR)</label><input type="number"
                min="0" step="0.01" max="9999999999" name="deductions" id="deductions" class="form-control"
                value="{{ old('deductions', $payroll?->deductions ?? 0) }}"></div>
        <div class="col-12">
            <div class="payslip-total">
                <div class="kv-label">Estimated net pay</div><strong id="net-preview" aria-live="polite">Rp 0</strong>
                <div class="form-hint">The final amount is calculated and validated on the server, not from this
                    preview.</div>
            </div>
        </div>
        <div class="col-12"><button class="btn btn-primary"
                type="submit">{{ $payroll ? 'Update payslip' : 'Create payslip' }}</button> <a class="btn btn-light"
                href="{{ route('payrolls.index') }}">Cancel</a></div>
    </div>
</form>
@push('scripts')
    <script>
        (() => {
            const ids = ['salary', 'bonuses', 'deductions'];
            const input = id => document.getElementById(id);
            const update = () => {
                const [base, bonus, deduction] = ids.map(id => Number.parseFloat(input(id).value) || 0);
                document.getElementById('net-preview').textContent = 'Rp ' + (base + bonus - deduction)
                    .toLocaleString('id-ID', {
                        maximumFractionDigits: 2
                    });
            };
            ids.forEach(id => input(id).addEventListener('input', update));
            document.getElementById('employee_id').addEventListener('change', function() {
                if (!input('salary').value) input('salary').value = this.selectedOptions[0]?.dataset.salary ??
                    '';
                update();
            });
            update();
        })();
    </script>
@endpush
