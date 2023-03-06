{{-- @extends('templates.development') --}}
@extends('templates.portal')
@section('content')
<div class="tilesListContent">
    {{-- @php
        $uploadedFileData = session('fileData');
    @endphp --}}
    <h1>{{ __('Tiles') }}</h1>
    {{-- {{ print_r($data['all_tiles_by_group']) }} --}}
    <p>{{ __('Set a tile for every group found within the portal') }}.</p>
    <h2>{{ __('Groups') }}</h2>
    <p>{{ __('Current active groups with its tile') }}.</p>
        @if(count($data['all_groups']))
        <table>
            <thead>
                <tr>
                    <th>id</th>
                    <th>{{ __('Group') }}</th>
                    <th>{{ __('Tile') }}</th>
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
                                    <button type="submit" onclick="return confirm('{{ __('You are about to delete the tile for group') }} {{ $group->group }}\n\n{{ __('Are you sure') }}?')" class="deleteBtn"></button>
                                </form>
                            </div>
                        @else
                            <p>{{ __('No image found') }}</p>
                            <form action="/tile" method="post" enctype="multipart/form-data">
                                {{-- @method('post') --}}
                                @csrf
                                <input type="hidden" name="group_name" value="{{ $group->group }}">
                                <label for="fileUploadRef" class="fileUpload">{{ __('Select file') }}</label>
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
        <p>{{ __('No groups found') }}</p>
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