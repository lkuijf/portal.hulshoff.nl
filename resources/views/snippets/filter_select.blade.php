<select name="{{ $filter_name }}" data-filter-reference="{{ $filter_reference }}">
    <option value="">- {{ __('Select') }} {{ $filter_name }} -</option>
    @foreach ($filter_options as $i => $option)
    @php
        $optionToDisplay = $option;
        if(app()->getLocale() == 'en' && $enTranslation = config('hulshoff.productgroup_translations.' . $optionToDisplay)) {
            $optionToDisplay = $enTranslation;
        }
    @endphp
    <option value="{{ $i }}" data-working-name="{{ $option }}"@if($option == $filter_selected_option && $option){{ ' selected' }}@endif>{{ $optionToDisplay }}</option>
    @endforeach
    {{-- <option value="Gispen" selected>Gispen</option> --}}
</select>