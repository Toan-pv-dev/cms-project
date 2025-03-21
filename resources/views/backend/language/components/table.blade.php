<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>
                <input type="checkbox" value="" id="checkAll" class="input-checkbox">
            </th>
            <th>Ảnh</th>
            <th>Tên ngôn ngữ</th>
            <th>Canonical</th>
            <th>Tình trạng</th>
            <th>Action</th>
        </tr>
    </thead>
    {{-- @dd($languages); --}}
    <tbody>
        @if (isset($languages) && is_object($languages))
            @foreach ($languages as $language)
                <tr>
                    <td>
                        <input type="checkbox" value="{{ $language->id }}" class="input-checkbox checkBoxItem">
                    </td>
                    <td style="width: 100px; height: 60px; text-align: center;">
                        <span><img style=" width: 100%;height: 100%;object-fit: cover; border-radius: 5px; "
                                src="{{ $language->image }}" alt=""></span>
                    </td>

                    <td>
                        {{ $language->name }}
                    </td>
                    <td>
                        {{ $language->canonical }}
                    </td>
                    {{-- <td>
                        {{ $language->image }}
                    </td> --}}
                    <td class="text-center js-switch-{{ $language->id }}">


                        {{-- {{ $user->id }} --}}
                        <input type="checkbox" data-field="publish" data-model="{{ $config['model'] }}"
                            class="js-switch status" {{ $language->publish == 1 ? 'checked' : '' }}
                            value="{{ $language->publish }}" data-modelId="{{ $language->id ?? '' }}" />

                    </td>
                    <td class="edit-btn-group" style="text-align:center; position: relative">
                        <div class="" style="position: relative, display: inline-block">
                            <a class="btn btn-success" href="{{ route('language.edit', $language->id) }}"><i
                                    class="fa fa-edit"></i></a>
                            <a class="btn btn-warning" href="{{ route('language.delete', $language->id) }}"><i
                                    class="fa fa-trash-o"></i></a>
                        </div>
                    </td>
                </tr>
            @endforeach
            {{-- @endisset --}}
        @endif
    </tbody>
</table>
{{ $languages->links('pagination::bootstrap-4') }}
