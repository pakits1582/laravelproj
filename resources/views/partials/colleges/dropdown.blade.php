<select name="{{ $fieldname ?? 'college' }}" id="{{ $fieldid ?? 'college' }}" class="form-control">
    <option value="">- select college -</option>
    @if ($colleges)
        @foreach ($colleges as $college)
            <option value="{{ $college->id }}" 
                {{ (old('college', $fieldname ?? 'college') == $college->id) ? 'selected' : '' }}
                {{ (isset($value)) ? ($value == $college->id) ? 'selected' : '' : '' }}
                >{{ $college->code }}</option>
        @endforeach
    @endif
</select>