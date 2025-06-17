<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>
                <input type="checkbox" value="" id="checkAll" class="input-checkbox">
            </th>
            <th>Tên nhóm</th>
            <th>Số thành viên</th>
            <th>Mô tả</th>
            <th style="text-align: center">Tình trạng</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @if (isset($user_catalogues) && is_object($user_catalogues))
            @foreach ($user_catalogues as $user_catalogue)
                <tr>
                    <td>
                        <input type="checkbox" value="{{ $user_catalogue->id }}" class="input-checkbox checkBoxItem">
                    </td>
                    <td>
                        {{ $user_catalogue->name }}
                    </td>
                    <td>
                        {{ $user_catalogue->users_count }} người
                    </td>
                    <td>
                        {{ $user_catalogue->description }}
                    </td>
                    <td class="text-center js-switch-{{ $user_catalogue->id }}">


                        {{-- {{ $user->id }} --}}
                        <input type="checkbox" data-field="publish" data-model="userCatalogue" class="js-switch status"
                            {{ $user_catalogue->publish == 1 ? 'checked' : '' }} value="{{ $user_catalogue->publish }}"
                            data-modelId="{{ $user_catalogue->id ?? '' }}" />

                    </td>
                    <td class="edit-btn-group" style="text-align:center; position: relative">
                        <div class="" style="position: relative, display: inline-block">
                            <a class="btn btn-success" href="{{ route('user.catalogue.edit', $user_catalogue->id) }}"><i
                                    class="fa fa-edit"></i></a>
                            <a class="btn btn-warning"
                                href="{{ route('user.catalogue.delete', $user_catalogue->id) }}"><i
                                    class="fa fa-trash-o"></i></a>
                        </div>
                    </td>
                </tr>
            @endforeach
            {{-- @endisset --}}
        @endif
    </tbody>
</table>
{{ $user_catalogues->links('pagination::bootstrap-4') }}
