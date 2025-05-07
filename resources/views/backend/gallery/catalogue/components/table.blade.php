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
        @if (isset($galleryCatalogues) && is_object($galleryCatalogues))
            @foreach ($galleryCatalogues as $galleryCatalogue)
                <tr>
                    <td>
                        <input type="checkbox" value="{{ $galleryCatalogue->id }}" class="input-checkbox checkBoxItem">
                    </td>
                    <td style="width: 100px; height: 60px; text-align: center;">
                        <span><img style=" width: 100%;height: 100%;object-fit: cover; "
                                src="{{ $galleryCatalogue->image }}" alt=""></span>
                    </td>

                    <td>
                        {{ str_repeat('|____', $galleryCatalogue->level > 0 ? $galleryCatalogue->level - 1 : 0) . $galleryCatalogue->name }}
                    </td>
                    @foreach ($languages as $language)
                        @if (session('locale') == $language->canonical)
                            @continue
                        @endif
                        <td class="text-center">
                            @php
                                $hasTranslation = $language->galleryCatalogue->contains($galleryCatalogue->id);
                            @endphp
                            @if ($hasTranslation)
                                <a
                                    href="{{ route('language.translate', ['id' => $galleryCatalogue->id, 'languageId' => $language->id, 'model' => 'GalleryCatalogue']) }}">Đã
                                    dịch</a>
                            @else
                                <a
                                    href="{{ route('language.translate', ['id' => $galleryCatalogue->id, 'languageId' => $language->id, 'model' => 'GalleryCatalogue']) }}">Chưa
                                    dịch</a>
                            @endif
                        </td>
                    @endforeach


                    <td class="text-center js-switch-{{ $galleryCatalogue->id }}">


                        {{-- {{ $user->id }} --}}
                        <input type="checkbox" data-field="publish" data-model="{{ $config['model'] }}"
                            class="js-switch status" {{ $galleryCatalogue->publish == 1 ? 'checked' : '' }}
                            value="{{ $galleryCatalogue->publish }}"
                            data-modelId="{{ $galleryCatalogue->id ?? '' }}" />

                    </td>
                    <td class="edit-btn-group" style="text-align:center; position: relative">
                        <div class="" style="position: relative, display: inline-block">
                            <a class="btn btn-success"
                                href="{{ route('gallery.catalogue.edit', $galleryCatalogue->id) }}"><i
                                    class="fa fa-edit"></i></a>
                            <a class="btn btn-warning"
                                href="{{ route('gallery.catalogue.delete', $galleryCatalogue->id) }}"><i
                                    class="fa fa-trash-o"></i></a>
                        </div>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
{{ $galleryCatalogues->links('pagination::bootstrap-4') }}
