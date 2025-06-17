<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>
                <input type="checkbox" value="" id="checkAll" class="input-checkbox">
            </th>
            <th>Tên nhóm</th>
            <th>Từ khóa</th>
            <th>Danh sách hình ảnh</th>
            <th>Tình trạng</th>
            <th>Action</th>


        </tr>
    </thead>
    <tbody>
        @if (isset($slides) && is_object($slides))
            @foreach ($slides as $key => $slide)
                <tr>
                    <td>
                        <input type="checkbox" value="{{ $slide->id }}" class="input-checkbox checkBoxItem">
                    </td>
                    <td>{{ $slide->name }}</td>


                    <td>
                        {{ $slide->keyword }}

                    </td>
                    <td class="uk-flex uk-flex-middle" style="border: none">
                        @foreach ($slide->item[$languageId] as $key => $val)
                            <div style="width: 60px; padding-right: 5px">
                                <img style="width: 100%; height: 100%;  border-radius: 5px;" src="{{ $val['name'] }}"
                                    alt="">
                            </div>
                        @endforeach
                    </td>


                    <td class="text-center js-switch-{{ $slide->id }}">


                        {{-- {{ $slide->id }} --}}
                        <input type="checkbox" data-field="publish" data-model="{{ $config['model'] }}"
                            class="js-switch status" {{ $slide->publish == 1 ? 'checked' : '' }}
                            value="{{ $slide->publish }}" data-modelId="{{ $slide->id ?? '' }}" />

                    </td>
                    <td class="edit-btn-group" style="text-align:center; position: relative">
                        <div class="" style="position: relative, display: inline-block">
                            <a class="btn btn-success" href="{{ route('slide.edit', $slide->id) }}"><i
                                    class="fa fa-edit"></i></a>
                            <a class="btn btn-warning" href="{{ route('slide.delete', $slide->id) }}"><i
                                    class="fa fa-trash-o"></i></a>

                        </div>
                    </td>
                </tr>
            @endforeach
            {{-- @endisset --}}
        @endif
    </tbody>
</table>
{{ $slides->links('pagination::bootstrap-4') }}
