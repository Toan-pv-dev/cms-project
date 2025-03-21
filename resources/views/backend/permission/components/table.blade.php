<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>
                <input type="checkbox" value="" id="checkAll" class="input-checkbox">
            </th>
            <th>Tiêu đề</th>
            <th>Canonical</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @if (isset($permissions) && is_object($permissions))
            @foreach ($permissions as $permission)
                <tr>
                    <td>
                        <input type="checkbox" value="{{ $permission->id }}" class="input-checkbox checkBoxItem">
                    </td>

                    <td>
                        {{ $permission->name }}
                    </td>
                    <td>
                        {{ $permission->canonical }}
                    </td>


                    {{-- {{ $user->id }} --}}
                    <td class="edit-btn-group" style="text-align:center; position: relative">
                        <div class="" style="position: relative, display: inline-block">
                            <a class="btn btn-success" href="{{ route('permission.edit', $permission->id) }}"><i
                                    class="fa fa-edit"></i></a>
                            <a class="btn btn-warning" href="{{ route('permission.delete', $permission->id) }}"><i
                                    class="fa fa-trash-o"></i></a>
                        </div>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
{{ $permissions->links('pagination::bootstrap-4') }}
