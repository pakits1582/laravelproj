<select name="{{ $fieldname ?? 'educational_level' }}" id="{{ $fieldid ?? 'educational_level' }}" class="form-control {{ $fieldclass ?? 'educational_level' }}">
    <option value="">- select level -</option>
    @if ($educlevels)
        @foreach ($educlevels as $educlevel)
            <option value="{{ $educlevel->id }}" 
                {{ (old($fieldname ?? 'educational_level') == $educlevel->id) ? 'selected' : '' }}
                {{ (isset($value)) ? ($value == $educlevel->id) ? 'selected' : '' : '' }}
                >{{ $educlevel->level }}</option>
        @endforeach
        @isset($addnew)
            @if ($addnew == 1)
                <option value="addnewlevel" id="addnewlevel">- Click to add new level -</option>  
            @endif
        @endisset
        
    @endif
</select>
@error($fieldname ?? 'educational_level')
    <p class="text-danger text-xs mt-1">{{$message}}</p>
@enderror