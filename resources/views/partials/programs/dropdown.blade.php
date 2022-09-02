<select name="{{ $fieldname ?? 'program' }}" id="{{ $fieldid ?? 'program' }}" class="form-control">
    <option value="">- select program -</option>
    @if ($programs)
        @foreach ($programs as $program)
            <option value="{{ $program->id }}" 
                {{ (old('program', $fieldname ?? 'program') == $program->id) ? 'selected' : '' }}
                {{ (isset($value)) ? ($value == $program->id) ? 'selected' : '' : '' }}
                >{{ $program->code }}</option>
        @endforeach
    @endif
</select>