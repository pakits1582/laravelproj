<select name="{{ $fieldname ?? 'program' }}" id="{{ $fieldid ?? 'program' }}" class="form-control  {{ $fieldclass ?? 'program' }}">
    <option value="">- select program -</option>
    @if ($programs)
        @foreach ($programs as $program)
            <option value="{{ $program->id }}" 
                {{ (old($fieldname ?? 'program') == $program->id) ? 'selected' : '' }}
                {{ (isset($value)) ? ($value == $program->id) ? 'selected' : '' : '' }}
                >( {{ $program->code }} ) {{ $program->name }}</option>
        @endforeach
    @endif
</select>
@error($fieldname ?? 'program')
    <p class="text-danger text-xs mt-1">{{$message}}</p>
@enderror