<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>
                <input type="checkbox" value="" id="checkAll" class="input-checkbox">
            </th>
            <th>Ảnh</th>
            <th>Tên nhóm</th>
            {{-- <th>Canonical</th> --}}
            <th>Tình trạng</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @if (isset($postCatalogues) && is_object($postCatalogues))
            @foreach ($postCatalogues as $postCatalogue)
                <tr>
                    <td>
                        <input type="checkbox" value="{{ $postCatalogue->id }}" class="input-checkbox checkBoxItem">
                    </td>
                    <td style="width: 100px; height: 60px; text-align: center;">
                        <span><img style=" width: 100%;height: 100%;object-fit: cover; border-radius: 5px; "
                                src="{{ $postCatalogue->image }}" alt=""></span>
                    </td>

                    <td>
                        {{ str_repeat('|____', $postCatalogue->level > 0 ? $postCatalogue->level - 1 : 0) . $postCatalogue->name }}
                    </td>
                    {{-- <td>
                        {{ $postCatalogue->image }}
                    </td> --}}
                    <td class="text-center js-switch-{{ $postCatalogue->id }}">


                        {{-- {{ $user->id }} --}}
                        <input type="checkbox" data-field="publish" data-model="postCatalogue" class="js-switch status"
                            {{ $postCatalogue->publish == 1 ? 'checked' : '' }} value="{{ $postCatalogue->publish }}"
                            data-modelId="{{ $postCatalogue->id ?? '' }}" />

                    </td>
                    <td class="edit-btn-group" style="text-align:center; position: relative">
                        <div class="" style="position: relative, display: inline-block">
                            <a class="btn btn-success" href="{{ route('post.catalogue.edit', $postCatalogue->id) }}"><i
                                    class="fa fa-edit"></i></a>
                            <a class="btn btn-warning"
                                href="{{ route('post.catalogue.delete', $postCatalogue->id) }}"><i
                                    class="fa fa-trash-o"></i></a>
                        </div>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
{{ $postCatalogues->links('pagination::bootstrap-4') }}
