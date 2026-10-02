@php($role = $role ?? null)
<form method="POST" action="{{ $role ? route('roles.update', $role) : route('roles.store') }}">
    @csrf @if ($role)
        @method('PUT')
    @endif
    <div class="row g-4">
        <div class="col-12"><label for="title" class="form-label">Role name *</label><input class="form-control"
                name="title" id="title" maxlength="255" value="{{ old('title', $role?->title) }}" @readonly($role?->title === 'HR')
                required>
            <p class="form-hint">The reserved HR role grants administrative access. Other names have employee-only
                access.</p>
        </div>
        @if ($role?->title === 'HR')
            <input type="hidden" name="title" value="HR">
        @endif
        <div class="col-12"><label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" maxlength="1000" rows="3" class="form-control">{{ old('description', $role?->description) }}</textarea>
        </div>
        <div class="col-12"><button class="btn btn-primary"
                type="submit">{{ $role ? 'Save changes' : 'Create role' }}</button> <a class="btn btn-light"
                href="{{ route('roles.index') }}">Cancel</a></div>
    </div>
</form>
