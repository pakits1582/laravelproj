<select name="{{ $fieldname ?? 'college' }}" id="{{ $fieldid ?? 'college' }}" class="form-control">
    <option value="">- select college -</option>
    @if ($colleges)
        @foreach ($colleges as $college)
            <option value="{{ $college->id }}" 
                {{ (old($fieldname ?? 'college') == $college->id) ? 'selected' : '' }}
                {{ (isset($value)) ? ($value == $college->id) ? 'selected' : '' : '' }}
                >{{ $college->code }}</option>
        @endforeach
    @endif
</select>
@error($fieldname ?? 'college')
    <p class="text-danger text-xs mt-1">{{$message}}</p>
@enderror