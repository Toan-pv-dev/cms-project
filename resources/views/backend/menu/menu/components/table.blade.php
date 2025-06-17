<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>
                <input type="checkbox" value="" id="checkAll" class="input-checkbox">
            </th>
            <th class="text-center">Tên Menu</th>
            <th class="text-center">Từ khóa</th>
            <th class="text-center">Tình trạng</th>
            <th class="text-center">Action</th>


        </tr>
    </thead>
    <tbody>
        @if (isset($menuCatalogues) && is_object($menuCatalogues))
            @foreach ($menuCatalogues as $menu)
                <tr>
                    <td>
                        <input type="checkbox" value="{{ $menu->id }}" class="input-checkbox checkBoxItem">
                    </td>
                    <td>
                        {{ $menu->name }}
                    </td>
                    <td>
                        {{ $menu->keyword }}
                    </td>
                    <td class="text-center js-switch-{{ $menu->id }}">


                        {{-- {{ $menu->id }} --}}
                        <input type="checkbox" data-field="publish" data-model="{{ $config['model'] }}"
                            class="js-switch status" {{ $menu->publish == 1 ? 'checked' : '' }}
                            value="{{ $menu->publish }}" data-modelId="{{ $menu->id ?? '' }}" />

                    </td>
                    <td class="edit-btn-group" style="text-align:center; position: relative">
                        <div class="" style="position: relative, display: inline-block">
                            <a class="btn btn-success" href="{{ route('menu.edit', ['id' => $menu->id]) }}"><i
                                    class="fa fa-edit"></i></a>
                            <a class="btn btn-warning" href="{{ route('menu.delete', ['id' => $menu->id]) }}"><i
                                    class="fa fa-trash-o"></i></a>

                        </div>
                    </td>


                </tr>
            @endforeach
            {{-- @endisset --}}
        @endif
    </tbody>
</table>
{{ $menuCatalogues->links('pagination::bootstrap-4') }}
