@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="https://laravel.com/img/notification-logo.png" class="logo" alt="Laravel Logo">
@else
{{-- <p>{{ $slot }}</p> --}}
<img src="{{ url('statics/hulshoff-logo.png') }}" alt="logo" />
@endif
</a>
</td>
</tr>
