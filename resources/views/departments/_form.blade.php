@php($department = $department ?? null)
<form method="POST" action="{{ $department ? route('departments.update', $department) : route('departments.store') }}">
    @csrf @if ($department)
        @method('PUT')
    @endif
    <div class="row g-4">
        <div class="col-md-8"><label for="name" class="form-label">Department name *</label><input class="form-control"
                id="name" name="name" maxlength="255" value="{{ old('name', $department?->name) }}" required></div>
        <div class="col-md-4"><label for="status" class="form-label">Status *</label><select name="status"
                id="status" class="form-select" required>
                <option value="active" @selected(old('status', $department?->status ?? 'active') === 'active')>Active</option>
                <option value="inactive" @selected(old('status', $department?->status) === 'inactive')>Inactive</option>
            </select></div>
        <div class="col-12"><label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" rows="3" class="form-control" maxlength="1000">{{ old('description', $department?->description) }}</textarea>
        </div>
        <div class="col-12"><button class="btn btn-primary"
                type="submit">{{ $department ? 'Save changes' : 'Create department' }}</button> <a
                class="btn btn-light" href="{{ route('departments.index') }}">Cancel</a></div>
    </div>
</form>
