<select name="{{ $fieldname ?? 'period' }}" id="{{ $fieldid ?? 'period' }}" class="form-control">
    <option value="">- select period -</option>
    @if ($periods)
        @foreach ($periods as $period)
            <option value="{{ $period->id }}" 
                {{ (old('period', $fieldname ?? 'period') == $period->id) ? 'selected' : '' }}
                {{ (isset($value)) ? ($value == $period->id) ? 'selected' : '' : '' }}
                >{{ $period->name }}</option>
        @endforeach
    @endif
</select>