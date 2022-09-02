<select name="{{ $fieldname ?? 'department' }}" id="{{ $fieldid ?? 'department' }}" class="form-control">
    <option value="">- select department -</option>
    @if ($departments)
        @foreach ($departments as $department)
            <option value="{{ $department->id }}" 
                {{ (old('department', $fieldname ?? 'department') == $department->id) ? 'selected' : '' }}
                {{ (isset($value)) ? ($value == $department->id) ? 'selected' : '' : '' }}
                >{{ $department->code }}</option>
        @endforeach
    @endif
</select>