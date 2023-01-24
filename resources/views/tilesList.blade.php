{{-- @extends('templates.development') --}}
@extends('templates.portal')
@section('content')
<div class="tilesListContent">
    {{-- @php
        $uploadedFileData = session('fileData');
    @endphp --}}
    <h1>Tiles</h1>
    {{-- {{ print_r($data['all_tiles_by_group']) }} --}}
    <p>Set tiles for groups</p>
    <h2>Groups</h2>
    <p>Current active groups with its tile.</p>
        @if(count($data['all_groups']))
        <table>
            <thead>
                <tr>
                    <th>id</th>
                    <th>Group</th>
                    <th>Tile</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($data['all_groups'] as $group)
                <tr>
                    <td>{{ $group->id }}</td>
                    <td>{{ $group->group }}</td>
                    <td>
                        @if (isset($data['all_tiles_by_group'][$group->group]))
                            <div class="imageIsSet">
                                <p><img src="{{ asset('storage/tiles') }}/{{ $data['all_tiles_by_group'][$group->group] }}" alt="tile" class="tileImg"></p>
                                <form action="/tile" method="post">
                                    @method('delete')
                                    @csrf
                                    <input type="hidden" name="group_name" value="{{ $group->group }}">
                                    <button type="submit" onclick="return confirm('You are about to delete the tile for group {{ $group->group }}\n\nAre you sure?')" class="deleteBtn"></button>
                                </form>
                            </div>
                        @else
                            <p>Geen afbeelding aanwezig</p>
                            <form action="/tile" method="post" enctype="multipart/form-data">
                                {{-- @method('post') --}}
                                @csrf
                                <input type="hidden" name="group_name" value="{{ $group->group }}">
                                <label for="fileUploadRef" class="fileUpload">Select file</label>
                                <input id="fileUploadRef" type="file" name="tileFile">
                                <button type="submit" class="uploadBtn">Upload</button>
                            </form>       
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        @else
        <p>No groups found</p>
        @endif
    {{-- </div> --}}
</div>
@endsection
{{-- @section('before_closing_body_tag')
@if ($uploadedFileData)
<script>
    console.log($uploadedFileData['file']);
</script>
@endif
@endsection --}}