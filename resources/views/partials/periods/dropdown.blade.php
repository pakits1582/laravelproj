<select name="{{ $fieldname ?? 'period' }}" id="{{ $fieldid ?? 'period' }}" class="form-control {{ $fieldclass ?? 'period' }}">
    <option value="">- select period -</option>
    @if ($periods)
        @foreach ($periods as $period)
            <option value="{{ $period->id }}" 
                {{ (old($fieldname ?? 'period') == $period->id) ? 'selected' : '' }}
                {{ (isset($value)) ? ($value == $period->id) ? 'selected' : '' : '' }}
                >{{ $period->name }}</option>
        @endforeach
    @endif
</select>
@error($fieldname ?? 'period')
    <p class="text-danger text-xs mt-1">{{$message}}</p>
@enderror