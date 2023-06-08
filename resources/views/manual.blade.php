@php
    $manId = '';
    $manUrl = '';
    $manText = '';
    $headerTxt = 'New';
    $postType = 'POST';
    if(isset($manual)) {
        $manId = $manual->id;
        $manUrl = $manual->url;
        $manText = $manual->text;
        $headerTxt = 'Edit';
        $postType = 'PUT';
    }
@endphp
@extends('templates.portal')
@section('content')
<div class="manualContent">
    <h1>{{ __($headerTxt) }} {{ Str::lower(__('Manual')) }}</h1>
    <p><a href="{{ route('manuals') }}" class="backBtn">{{ __('Back to overview') }}</a></p>
    <form action="{{ url('manual') }}" method="post">
        @method($postType)
        @csrf
        <input type="hidden" name="id" value="{{ $manId }}">
        <table>
            <tr>
                <td>{{ __('Url') }}</td>
                <td><input type="text" name="url" size="80" value="@if(old('url')){{ old('url') }}@else{{ $manUrl }}@endif"></td>
            </tr>
            <tr>
                <td>{{ __('Text') }}</td>
                <td>
                    <textarea id="wysiwyg" name="text" rows="10" cols="100">{!! $manText !!}</textarea>
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td><button type="submit" class="saveBtn">{{ __('Save') }}</button></td>
            </tr>
        </table>
    </form>
</div>
@endsection
@section('extra_head')
    <link href="{{ asset('css/suneditor.min.css') }}" rel="stylesheet">
    <script src="{{ asset('js/suneditor.min.js') }}"></script>
@endsection
@section('before_closing_body_tag')
<script>
    const editor = SUNEDITOR.create((document.getElementById('wysiwyg') || 'wysiwyg'),{
        // All of the plugins are loaded in the "window.SUNEDITOR" object in dist/suneditor.min.js file
        // Insert options
        // Language global object (default: en)
        // lang: SUNEDITOR_LANG['ko']
        defaultStyle: "font-family: Arial, Helvetica, sans-serif; font-size: 18px;"
    });
    const form = document.querySelector('.manualContent form');
    form.addEventListener('submit', (e) => {
        e.preventDefault(); // wait with submitting
        editor.save(); // Copies the contents of the suneditor into the <textarea>
        form.submit(); // re-submit
    });
</script>
@endsection