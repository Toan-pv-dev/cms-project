<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>
                <input type="checkbox" value="" id="checkAll" class="input-checkbox">
            </th>
            <th>Tiêu đề</th>
            <th style="width: 80px" class="text-center">Vị trí</th>
            <th class="text-center">Tình trạng</th>

            <th class="text-center">Action</th>
        </tr>
    </thead>
    <tbody>
        {{-- @dd($moduleNames); --}}
        @if (isset($moduleNames) && is_object($moduleNames))
            @foreach ($moduleNames as $moduleName)
                <tr id="{{ $moduleName->id }}">
                    <td>
                        <input type="checkbox" value="{{ $moduleName->id }}" class="input-checkbox checkBoxItem">
                    </td>

                    <td>
                        <div class="uk-flex uk-flex-middle">
                            <div class="image mr5 image-moduleName">
                                <img src="{{ $moduleName->image }}" alt="{{ $moduleName->name }}" class="img-cover">
                            </div>
                            <div class="main-info">
                                <div class="main_title">
                                    <span>{{ $moduleName->name }}</span>
                                </div>
                                <div class="catalogues">
                                    <span class="text-danger">Nhóm hiển thị</span>
                                    @foreach ($moduleName->moduleName_catalogues as $val)
                                        @foreach ($val->moduleName_catalogue_language as $cat)
                                            <a href="">{{ $cat->name }}</a>
                                        @endforeach
                                    @endforeach

                                </div>

                            </div>

                        </div>
                    </td>
                    <td>
                        <input type="number" name="order" class="form-control sort-order text-center"
                            data-id="{{ $moduleName->id }}" data-model="{{ $config['model'] }}"
                            value="{{ $moduleName->order }}">
                    </td>

                    <td class="text-center js-switch-{{ $moduleName->id }}">
                        <input type="checkbox" data-field="publish" data-model="{{ $config['model'] }}"
                            class="js-switch status" {{ $moduleName->publish == 1 ? 'checked' : '' }}
                            value="{{ $moduleName->publish }}" data-modelId="{{ $moduleName->id ?? '' }}" />

                    </td>
                    <td class="edit-btn-group" style="text-align:center; position: relative">
                        <div class="" style="position: relative, display: inline-block">
                            <a class="btn btn-success" href="{{ route('moduleName.edit', $moduleName->id) }}"><i
                                    class="fa fa-edit"></i></a>
                            <a class="btn btn-warning" href="{{ route('moduleName.delete', $moduleName->id) }}"><i
                                    class="fa fa-trash-o"></i></a>
                        </div>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
{{ $moduleNames->links('pagination::bootstrap-4') }}
