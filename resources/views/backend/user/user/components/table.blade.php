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
        @if (isset($users) && is_object($users))
            @foreach ($users as $user)
                <tr>
                    <td>
                        <input type="checkbox" value="{{ $user->id }}" class="input-checkbox checkBoxItem">
                    </td>

                    <td style="width: 100px; height: 60px; text-align:center;vertical-align: middle;">
                        <span>

                            <img style="width: 100%; height: 100%; object-fit: cover; border-radius: 5px;"
                                src="{{ $user->image }}" alt="">
                        </span>

                    </td>
                    <td>
                        {{ $user->name }}
                    </td>
                    <td>
                        {{ $user->userCatalogue->name }}
                    </td>
                    <td>
                        {{ $user->email }}
                    </td>
                    <td>
                        {{ $user->phone }}
                    </td>
                    <td>
                        {{ $user->address }}
                    </td>
                    <td class="text-center js-switch-{{ $user->id }}">


                        {{-- {{ $user->id }} --}}
                        <input type="checkbox" data-field="publish" data-model="User" class="js-switch status"
                            {{ $user->publish == 1 ? 'checked' : '' }} value="{{ $user->publish }}"
                            data-modelId="{{ $user->id ?? '' }}" />

                    </td>
                    <td class="edit-btn-group" style="text-align:center; position: relative">
                        <div class="" style="position: relative, display: inline-block">
                            <a class="btn btn-success" href="{{ route('user.edit', $user->id) }}"><i
                                    class="fa fa-edit"></i></a>
                            <a class="btn btn-warning" href="{{ route('user.delete', $user->id) }}"><i
                                    class="fa fa-trash-o"></i></a>
                        </div>
                    </td>
                </tr>
            @endforeach
            {{-- @endisset --}}
        @endif
    </tbody>
</table>
{{ $users->links('pagination::bootstrap-4') }}
