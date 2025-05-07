<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>
                <input type="checkbox" value="" id="checkAll" class="input-checkbox">
            </th>
            <th>Ảnh</th>
            <th>Tên module</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @if (isset($generates) && is_object($generates))
            @foreach ($generates as $generate)
                <tr>
                    <td>
                        <input type="checkbox" value="{{ $generate->id }}" class="input-checkbox checkBoxItem">
                    </td>
                    <td style="width: 100px; height: 60px; text-align: center;">
                        <span><img style=" width: 100%;height: 100%;object-fit: cover; border-radius: 5px; "
                                src="{{ $generate->image }}" alt=""></span>
                    </td>
                    <td>
                        {{ $generate->name }}
                    </td>
                    <td class="edit-btn-group" style="text-align:center; position: relative">
                        <div class="" style="position: relative, display: inline-block">
                            <a class="btn btn-success" href="{{ route('generate.edit', $generate->id) }}"><i
                                    class="fa fa-edit"></i></a>
                            <a class="btn btn-warning" href="{{ route('generate.delete', $generate->id) }}"><i
                                    class="fa fa-trash-o"></i></a>
                        </div>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
{{ $generates->links('pagination::bootstrap-4') }}
