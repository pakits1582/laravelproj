<select name="{{ $fieldname ?? 'educational_level' }}" id="{{ $fieldid ?? 'educational_level' }}" class="form-control">
    <option value="">- select level -</option>
    @if ($educlevels)
        @foreach ($educlevels as $educlevel)
            <option value="{{ $educlevel->id }}" 
                {{ (old('educational_level', $fieldname ?? 'educational_level') == $educlevel->id) ? 'selected' : '' }}
                {{ (isset($value)) ? ($value == $educlevel->id) ? 'selected' : '' : '' }}
                >{{ $educlevel->level }}</option>
        @endforeach
    @endif
</select>