<table class="table table-striped table-bordered language-table">
    <thead>
        <tr>
            <th>
                <input type="checkbox" value="" id="checkAll" class="input-checkbox">
            </th>
            <th>Ảnh</th>
            <th>Tên nhóm</th>
            @foreach ($languages as $language)
                @if (session('locale') == $language->canonical)
                    @continue
                @endif
                <th style="width: 120px; height: 50px; text-align: center; vertical-align: middle;">
                    <div style="display: flex; align-items: center; justify-content: center; width: 100%; height: 100%;">
                        <img src="{{ $language->image }}" alt=""
                            style="max-width: 60%; max-height: 50%; object-fit: cover;">
                    </div>
                </th>
            @endforeach

            <th>Tình trạng</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @if (isset($moduleNames) && is_object($moduleNames))
            @foreach ($moduleNames as $moduleName)
                <tr>
                    <td>
                        <input type="checkbox" value="{{ $moduleName->id }}" class="input-checkbox checkBoxItem">
                    </td>
                    <td style="width: 100px; height: 60px; text-align: center;">
                        <span><img style=" width: 100%;height: 100%;object-fit: cover; " src="{{ $moduleName->image }}"
                                alt=""></span>
                    </td>

                    <td>
                        {{ str_repeat('|____', $moduleName->level > 0 ? $moduleName->level - 1 : 0) . $moduleName->name }}
                    </td>
                    @foreach ($languages as $language)
                        @if (session('locale') == $language->canonical)
                            @continue
                        @endif
                        <td class="text-center">
                            @php
                                $hasTranslation = $language->moduleName->contains($moduleName->id);
                            @endphp
                            @if ($hasTranslation)
                                <a
                                    href="{{ route('language.translate', ['id' => $moduleName->id, 'languageId' => $language->id, 'model' => 'ModuleName']) }}">Đã
                                    dịch</a>
                            @else
                                <a
                                    href="{{ route('language.translate', ['id' => $moduleName->id, 'languageId' => $language->id, 'model' => 'ModuleName']) }}">Chưa
                                    dịch</a>
                            @endif
                        </td>
                    @endforeach


                    <td class="text-center js-switch-{{ $moduleName->id }}">


                        {{-- {{ $user->id }} --}}
                        <input type="checkbox" data-field="publish" data-model="{{ $config['model'] }}"
                            class="js-switch status" {{ $moduleName->publish == 1 ? 'checked' : '' }}
                            value="{{ $moduleName->publish }}" data-modelId="{{ $moduleName->id ?? '' }}" />

                    </td>
                    <td class="edit-btn-group" style="text-align:center; position: relative">
                        <div class="" style="position: relative, display: inline-block">
                            <a class="btn btn-success" href="{{ route('viewPath.edit', $moduleName->id) }}"><i
                                    class="fa fa-edit"></i></a>
                            <a class="btn btn-warning" href="{{ route('viewPath.delete', $moduleName->id) }}"><i
                                    class="fa fa-trash-o"></i></a>
                        </div>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
{{ $moduleNames->links('pagination::bootstrap-4') }}
