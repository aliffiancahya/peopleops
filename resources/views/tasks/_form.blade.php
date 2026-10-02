@php($task = $task ?? null)
<form method="POST" action="{{ $task ? route('tasks.update', $task) : route('tasks.store') }}">
    @csrf @if ($task)
        @method('PUT')
    @endif
    <div class="row g-4">
        <div class="col-12"><label class="form-label" for="title">Task title *</label><input class="form-control"
                name="title" id="title" maxlength="255" value="{{ old('title', $task?->title) }}" required></div>
        <div class="col-12"><label class="form-label" for="description">Description</label>
            <textarea class="form-control" name="description" id="description" rows="4" maxlength="5000">{{ old('description', $task?->description) }}</textarea>
        </div>
        <div class="col-md-5"><label class="form-label" for="assigned_to">Assigned employee *</label><select
                class="form-select" name="assigned_to" id="assigned_to" required>
                <option value="">Choose employee</option>
                @foreach ($employees as $employee)
                    <option value="{{ $employee->id }}" @selected(old('assigned_to', $task?->assigned_to) == $employee->id)>{{ $employee->fullname }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4"><label class="form-label" for="due_date">Due date *</label><input type="date"
                class="form-control" name="due_date" id="due_date"
                value="{{ old('due_date', $task?->due_date?->format('Y-m-d')) }}" required></div>
        <div class="col-md-3"><label class="form-label" for="status">Status *</label><select class="form-select"
                name="status" id="status" required>
                @foreach (['pending' => 'Pending', 'on progress' => 'In progress', 'done' => 'Done'] as $value => $label)
                    <option value="{{ $value }}" @selected(old('status', $task?->status ?? 'pending') === $value)>{{ $label }}</option>
                @endforeach
            </select></div>
        <div class="col-12"><button class="btn btn-primary"
                type="submit">{{ $task ? 'Save changes' : 'Assign task' }}</button> <a class="btn btn-light"
                href="{{ route('tasks.index') }}">Cancel</a></div>
    </div>
</form>
