<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>
                <input type="checkbox" value="" id="checkAll" class="input-checkbox">
            </th>
            <th>Ảnh</th>
            <th>Họ tên</th>
            <th>Nhóm thành viên</th>
            <th>Email</th>
            <th>Số điện thoại</th>
            <th>Địa chỉ</th>
            <th>Tình trạng</th>
            <th>Action</th>


        </tr>
    </thead>
    <tbody>
        @if (isset($widgets) && is_object($widgets))
            @foreach ($widgets as $widget)
                <tr>
                    <td>
                        <input type="checkbox" value="{{ $widget->id }}" class="input-checkbox checkBoxItem">
                    </td>

                    <td style="width: 100px; height: 60px; text-align:center;vertical-align: middle;">
                        <span>

                            <img style="width: 100%; height: 100%; object-fit: cover; border-radius: 5px;"
                                src="{{ $widget->image }}" alt="">
                        </span>

                    </td>
                    <td>
                        {{ $widget->name }}
                    </td>
                    <td>
                        {{ $widget->widgetCatalogue->name }}
                    </td>
                    <td>
                        {{ $widget->email }}
                    </td>
                    <td>
                        {{ $widget->phone }}
                    </td>
                    <td>
                        {{ $widget->address }}
                    </td>
                    <td class="text-center js-switch-{{ $widget->id }}">


                        {{-- {{ $widget->id }} --}}
                        <input type="checkbox" data-field="publish" data-model="{{ $config['model'] }}"
                            class="js-switch status" {{ $widget->publish == 1 ? 'checked' : '' }}
                            value="{{ $widget->publish }}" data-modelId="{{ $widget->id ?? '' }}" />

                    </td>
                    <td class="edit-btn-group" style="text-align:center; position: relative">
                        <div class="" style="position: relative, display: inline-block">
                            <a class="btn btn-success" href="{{ route('widget.edit', $widget->id) }}"><i
                                    class="fa fa-edit"></i></a>
                            <a class="btn btn-warning" href="{{ route('widget.delete', $widget->id) }}"><i
                                    class="fa fa-trash-o"></i></a>

                        </div>
                    </td>
                </tr>
            @endforeach
            {{-- @endisset --}}
        @endif
    </tbody>
</table>
{{ $widgets->links('pagination::bootstrap-4') }}
