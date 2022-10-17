<select name="{{ $filter_name }}">
    <option value="">- Selecteer {{ $filter_name }} -</option>
    @foreach ($filter_options as $option)
    <option value="{{ $option }}"@if($option == $filter_selected_option){{ ' selected' }}@endif>{{ $option }}</option>
    @endforeach
    {{-- <option value="Gispen" selected>Gispen</option> --}}
</select>