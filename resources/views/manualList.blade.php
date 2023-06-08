@extends('templates.portal')
@section('content')
<div class="manualsContent">
    <h1>{{ __('Overview') }} {{ Str::lower(__('Manuals')) }}</h1>
    <p>{{ __('Overview of all manuals') }}.</p>
    <p><a href="{{ route('new_manual') }}" class="addBtn">{{ __('Create new') }}</a></p>
    @if (isset($data['manuals']) && count($data['manuals']))
    <table>
        <thead>
            <tr>
                <th>{{ __('Url') }}</th>
                <th>&nbsp;</th>
            </tr>
        </thead>
        <tbody>
           
                @foreach ($data['manuals'] as $manual)
                <tr>
                    <td>{{ $manual->url }}</td>
                    <td>
                        <a href="{{ route('manual_detail', ['id' => $manual->id]) }}" class="editBtn">{{ __('Edit') }}</a>
                        <form action="/manual" method="post">
                            @method('delete')
                            @csrf
                            <input type="hidden" name="id" value="{{ $manual->id }}">
                            <button type="submit" onclick="return confirm('{{ __('You are about to delete the manual for url') }} {{ $manual->url }}\n\n{{ __('Are you sure') }}?')" class="deleteBtn"></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            
        </tbody>
    </table>
    @else
    <p>{{ __('No manuals found') }}</p>
    @endif
</div>
@endsection