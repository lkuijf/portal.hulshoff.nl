@if (auth()->user()->is_admin)
    <h1>Overzicht gebruikers</h1>
    <p><a href="{{ route('new_user') }}">[new user]</a></p>
    <table>
        <tr>
            <th>Name</th>
            <th>E-mail adres</th>
            <th>Klant</th>
            <th>Extra E-mail adressen</th>
            <th>Privileges</th>
            <th>Can reserve?</th>
            <th>&nbsp;</th>
        </tr>
    @foreach ($users as $user)
        <tr>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->klantCode }}</td>
            <td>@if($user->extra_email !== null){{ implode(', ', array_column(json_decode($user->extra_email,true),'email')) }}@endif</td>
            <td>@if($user->privileges !== null){{ implode(', ', json_decode($user->privileges,true)) }}@endif</td>
            {{-- @if($user->privileges !== null)@dd($user->privileges)@endif --}}
            {{-- <td>@if($user->privileges !== null){{ implode(', ', json_decode('[aasasd,66]',true)) }}@endif</td> --}}
            <td>{{ $user->can_reserve?'Ja':'Nee' }}</td>
            <td><a href="{{ route('users') }}/{{ $user->id }}">[edit]</a><a href="">[remove]</a></td>
        </tr>
    @endforeach
</table>
@else
    GEEN TOEGANG
@endif