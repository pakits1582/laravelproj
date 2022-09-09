<select name="{{ $fieldname ?? 'department' }}" id="{{ $fieldid ?? 'department' }}" class="form-control">
    <option value="">- select department -</option>
    @if ($departments)
        @foreach ($departments as $department)
            <option value="{{ $department->id }}" 
                {{ (old($fieldname ?? 'department') == $department->id) ? 'selected' : '' }}
                {{ (isset($value)) ? ($value == $department->id) ? 'selected' : '' : '' }}
                >{{ $department->code }}</option>
        @endforeach
    @endif
</select>
@error($fieldname ?? 'department')
    <p class="text-danger text-xs mt-1">{{$message}}</p>
@enderror