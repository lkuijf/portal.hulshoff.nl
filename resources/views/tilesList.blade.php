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
                    <th>&nbsp;</th>
                    <th>id</th>
                    <th>{{ __('Group') }}</th>
                    <th>{{ __('Tile') }}</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($data['all_groups'] as $group)
                <tr>
                    <td>
                        <form action="/productgroup" method="post" class="deleteProductgroupForm">
                            @method('delete')
                            @csrf
                            <input type="hidden" name="id" value="{{ $group->id }}">
                            <button type="submit" onclick="return confirm('{{ __('You are about to delete the group') }} {{ $group->group }}\n\n{{ __('All products within will be deleted!') }}\n{{ __('The tile image will be deleted!') }}\n\n{{ __('Are you sure') }}?')" class="deleteBtn"> {{ __('Delete') . ' ' . strtolower(__('Productgroup')) }}</button>
                        </form>
                    </td>
                    <td>{{ $group->id }}</td>
                    <td><div>{{ $group->group }}<a href="" class="editBtn editGroupNameBtn" data-group-id="{{ $group->id }}" data-group-name="{{ $group->group }}">{{ __('Edit') }}</a><br><span style="font-size:0.8em;">{{ __('Product count') }}: {{ $group->productCount }}</span></div></td>
                    <td>
                        @if (isset($data['all_tiles_by_group'][$group->group]))
                            <div class="imageIsSet">
                                <p><img src="{{ asset('tile_images') }}/{{ $data['all_tiles_by_group'][$group->group] }}" alt="tile" class="tileImg"></p>
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
@section('before_closing_body_tag')
<script>
    const editGroupNameBtns = document.querySelectorAll('.editGroupNameBtn');

    if(editGroupNameBtns.length) {
        editGroupNameBtns.forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                let parentWrapper = btn.parentNode.parentNode;
                let originalElement = btn.parentNode;

                let id = btn.dataset.groupId;
                let name = btn.dataset.groupName;

                let csrfToken = document.querySelector('meta[name="_token"]').content;
                let editForm = document.createElement('form');
                let editInput = document.createElement('input');
                let editHiddenMethod = document.createElement('input');
                let editHiddenToken = document.createElement('input');
                let editHiddenId = document.createElement('input');
                let editSave = document.createElement('button');
                let editCancel = document.createElement('a');
                editForm.setAttribute('action', '/productgroup');
                editForm.setAttribute('method', 'post');
                editInput.setAttribute('type', 'text');
                editInput.setAttribute('size', '25');
                editInput.setAttribute('name', 'group_name');
                editInput.setAttribute('value', name);
                editHiddenMethod.setAttribute('type', 'hidden');
                editHiddenMethod.setAttribute('name', '_method');
                editHiddenMethod.setAttribute('value', 'put');
                editHiddenToken.setAttribute('type', 'hidden');
                editHiddenToken.setAttribute('name', '_token');
                editHiddenToken.setAttribute('value', csrfToken);
                editHiddenId.setAttribute('type', 'hidden');
                editHiddenId.setAttribute('name', 'id');
                editHiddenId.setAttribute('value', id);

                editSave.setAttribute('type', 'submit');
                editCancel.setAttribute('href', '');

                let saveBtnText = document.createTextNode('{{ __('Save') }}');
                let cancelText = document.createTextNode('{{ __('Cancel') }}');
                editSave.appendChild(saveBtnText);
                editCancel.appendChild(cancelText);

                editForm.append(editHiddenMethod, editHiddenToken, editHiddenId, editInput, editSave, editCancel);

                parentWrapper.replaceChild(editForm, originalElement);

                editCancel.addEventListener('click', (e) => {
                    e.preventDefault();
                    parentWrapper.replaceChild(originalElement, editForm);
                    toggleVisibility(editGroupNameBtns);
                });
                toggleVisibility(editGroupNameBtns);

            });
        });
    }
    function toggleVisibility(elements) {
        elements.forEach(element => {
            if(element.style.visibility != 'hidden') {
                element.style.visibility = 'hidden';
            } else {
                element.style.visibility = '';
            }
        });
    }
</script>
@endsection