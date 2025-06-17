<table class="table table-striped table-bordered language-table">
    <thead>
        <tr>
            <th>
                <input type="checkbox" value="" id="checkAll" class="input-checkbox">
            </th>
            <th>Ảnh</th>
            <th>Tên nhóm</th>
            @foreach ($languages as $language)
                @if (session('app_locale') == $language->canonical)
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
        @if (isset($postCatalogues) && is_object($postCatalogues))
            @foreach ($postCatalogues as $postCatalogue)
                <tr>
                    <td>
                        <input type="checkbox" value="{{ $postCatalogue->id }}" class="input-checkbox checkBoxItem">
                    </td>
                    <td style="width: 100px; height: 60px; text-align: center;">
                        <span><img style=" width: 100%;height: 100%;object-fit: cover; "
                                src="{{ $postCatalogue->image }}" alt=""></span>
                    </td>

                    <td>
                        {{ str_repeat('|____', $postCatalogue->level > 0 ? $postCatalogue->level - 1 : 0) . $postCatalogue->name }}
                    </td>
                    @foreach ($languages as $language)
                        @if (session('app_locale') == $language->canonical)
                            @continue
                        @endif
                        <td class="text-center">
                            @php
                                $hasTranslation = $language->postCatalogue->contains($postCatalogue->id);
                            @endphp
                            @if ($hasTranslation)
                                <a
                                    href="{{ route('language.translate', ['id' => $postCatalogue->id, 'languageId' => $language->id, 'model' => 'PostCatalogue']) }}">Đã
                                    dịch</a>
                            @else
                                <a
                                    href="{{ route('language.translate', ['id' => $postCatalogue->id, 'languageId' => $language->id, 'model' => 'PostCatalogue']) }}">Chưa
                                    dịch</a>
                            @endif
                        </td>
                    @endforeach


                    <td class="text-center js-switch-{{ $postCatalogue->id }}">


                        {{-- {{ $user->id }} --}}
                        <input type="checkbox" data-field="publish" data-model="{{ $config['model'] }}"
                            class="js-switch status" {{ $postCatalogue->publish == 1 ? 'checked' : '' }}
                            value="{{ $postCatalogue->publish }}" data-modelId="{{ $postCatalogue->id ?? '' }}" />

                    </td>
                    <td class="edit-btn-group" style="text-align:center; position: relative">
                        <div class="" style="position: relative, display: inline-block">
                            <a class="btn btn-success" href="{{ route('post.catalogue.edit', $postCatalogue->id) }}"><i
                                    class="fa fa-edit"></i></a>
                            <a class="btn btn-warning"
                                href="{{ route('post.catalogue.delete', $postCatalogue->id) }}"><i
                                    class="fa fa-trash-o"></i></a>
                        </div>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
{{ $postCatalogues->links('pagination::bootstrap-4') }}
