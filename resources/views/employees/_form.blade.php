@php($emp = $employee ?? null)
<form method="POST" action="{{ $emp ? route('employees.update', $emp) : route('employees.store') }}">
    @csrf @if ($emp)
        @method('PUT')
    @endif
    <div class="row g-4">
        <div class="col-12">
            <h4 class="mb-0">Personal details</h4>
            <p class="text-muted small mb-0">Basic information visible to HR administrators.</p>
        </div>
        <div class="col-md-6"><label for="fullname" class="form-label">Full name *</label><input name="fullname"
                id="fullname" class="form-control" maxlength="255" value="{{ old('fullname', $emp?->fullname) }}"
                required></div>
        <div class="col-md-6"><label for="email" class="form-label">Work email *</label><input type="email"
                name="email" id="email" class="form-control" maxlength="255"
                value="{{ old('email', $emp?->email) }}" required></div>
        <div class="col-md-6"><label for="phone_number" class="form-label">Phone</label><input name="phone_number"
                id="phone_number" class="form-control" maxlength="30"
                value="{{ old('phone_number', $emp?->phone_number) }}"></div>
        <div class="col-md-3"><label for="birth_date" class="form-label">Date of birth *</label><input type="date"
                name="birth_date" id="birth_date" class="form-control"
                value="{{ old('birth_date', $emp?->birth_date?->format('Y-m-d')) }}" required></div>
        <div class="col-md-3"><label for="hire_date" class="form-label">Hire date *</label><input type="date"
                name="hire_date" id="hire_date" class="form-control"
                value="{{ old('hire_date', $emp?->hire_date?->format('Y-m-d')) }}" required></div>
        <div class="col-12"><label for="address" class="form-label">Address</label>
            <textarea name="address" id="address" rows="2" class="form-control" maxlength="1000">{{ old('address', $emp?->address) }}</textarea>
        </div>
        <div class="col-12">
            <hr>
            <h4>Employment details</h4>
        </div>
        <div class="col-md-6"><label for="department_id" class="form-label">Department *</label><select
                name="department_id" id="department_id" class="form-select" required>
                <option value="">Choose department</option>
                @foreach ($departments as $department)
                    <option value="{{ $department->id }}" @selected(old('department_id', $emp?->department_id) == $department->id)>{{ $department->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6"><label for="role_id" class="form-label">Role *</label><select name="role_id"
                id="role_id" class="form-select" required>
                <option value="">Choose role</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}" @selected(old('role_id', $emp?->role_id) == $role->id)>{{ $role->title }}</option>
                @endforeach
            </select></div>
        <div class="col-md-6"><label for="salary" class="form-label">Monthly base salary (IDR) *</label><input
                type="number" name="salary" id="salary" class="form-control" min="0" max="9999999999"
                step="0.01" value="{{ old('salary', $emp?->salary) }}" required></div>
        <div class="col-md-6"><label for="status" class="form-label">Employment status *</label><select name="status"
                id="status" class="form-select" required>
                <option value="active" @selected(old('status', $emp?->status ?? 'active') === 'active')>Active</option>
                <option value="inactive" @selected(old('status', $emp?->status) === 'inactive')>Inactive</option>
            </select></div>
        <div class="col-12">
            <hr>
            <h4>Portal access</h4>
            <p class="text-muted small">
                {{ $emp?->user ? 'Leave this blank to keep their current password.' : 'Optional: enter a password to create this employee’s portal login.' }}
                Only share credentials privately.</p>
        </div>
        <div class="col-md-6"><label for="portal_password"
                class="form-label">{{ $emp?->user ? 'New portal password' : 'Initial portal password' }}</label><input
                type="password" name="portal_password" id="portal_password" class="form-control" minlength="8"
                autocomplete="new-password"></div>
        <div class="col-md-6"><label for="portal_password_confirmation" class="form-label">Confirm portal
                password</label><input type="password" name="portal_password_confirmation"
                id="portal_password_confirmation" minlength="8" class="form-control" autocomplete="new-password">
        </div>
        <div class="col-12"><button class="btn btn-primary"
                type="submit">{{ $emp ? 'Save employee' : 'Add employee' }}</button> <a class="btn btn-light"
                href="{{ route('employees.index') }}">Cancel</a></div>
    </div>
</form>
