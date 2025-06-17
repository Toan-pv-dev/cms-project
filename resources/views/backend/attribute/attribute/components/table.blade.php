<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>
                <input type="checkbox" value="" id="checkAll" class="input-checkbox">
            </th>
            <th>Tiêu đề</th>
            @foreach ($languages as $language)
                @if (session('app_locale') == $language->canonical)
                    @continue
                @else
                    <th style="width: 120px; height: 50px; text-align: center; vertical-align: middle;">
                        <div
                            style="display: flex; align-items: center; justify-content: center; width: 100%; height: 100%;">
                            <img src="{{ $language->image }}" alt=""
                                style="max-width: 60%; max-height: 50%; object-fit: cover;">
                        </div>
                    </th>
                @endif
            @endforeach
            <th style="width: 80px" class="text-center">Vị trí</th>
            <th class="text-center">Tình trạng</th>

            <th class="text-center">Action</th>
        </tr>
    </thead>
    <tbody>
        {{-- @dd($attributes); --}}
        @if (isset($attributes) && is_object($attributes))
            @foreach ($attributes as $attribute)
                <tr id="{{ $attribute->id }}">
                    <td>
                        <input type="checkbox" value="{{ $attribute->id }}" class="input-checkbox checkBoxItem">
                    </td>

                    <td>
                        <div class="uk-flex uk-flex-middle">
                            <div class="image mr5 image-post">
                                <img src="{{ $attribute->image }}" alt="{{ $attribute->name }}" class="img-cover">
                            </div>
                            <div class="main-info">
                                <div class="main_title">
                                    <span>{{ $attribute->name }}</span>
                                </div>
                                <div class="catalogues">
                                    <span class="text-danger">Nhóm hiển thị</span>
                                    @foreach ($attribute->attribute_catalogues as $val)
                                        @foreach ($val->attribute_catalogue_language as $cat)
                                            <a href="">{{ $cat->name }}</a>
                                        @endforeach
                                    @endforeach

                                </div>

                            </div>

                        </div>
                    </td>
                    @foreach ($languages as $language)
                        @if (session('app_locale') == $language->canonical)
                            @continue
                        @else
                            <td class="text-center">
                                @php
                                    $hasTranslation = $language->attribute->contains($attribute->id);
                                @endphp
                                @if ($hasTranslation)
                                    <a
                                        href="{{ route('language.translate', ['id' => $attribute->id, 'languageId' => $language->id, 'model' => 'Attribute']) }}">Đã
                                        dịch</a>
                                @else
                                    <a
                                        href="{{ route('language.translate', ['id' => $attribute->id, 'languageId' => $language->id, 'model' => 'Attribute']) }}">Chưa
                                        dịch</a>
                                @endif
                            </td>
                        @endif
                    @endforeach
                    <td>
                        <input type="number" name="order" class="form-control sort-order text-center"
                            data-id="{{ $attribute->id }}" data-model="{{ $config['model'] }}"
                            value="{{ $attribute->order }}">
                    </td>

                    <td class="text-center js-switch-{{ $attribute->id }}">
                        <input type="checkbox" data-field="publish" data-model="{{ $config['model'] }}"
                            class="js-switch status" {{ $attribute->publish == 1 ? 'checked' : '' }}
                            value="{{ $attribute->publish }}" data-modelId="{{ $attribute->id ?? '' }}" />

                    </td>
                    <td class="edit-btn-group" style="text-align:center; position: relative">
                        <div class="" style="position: relative, display: inline-block">
                            <a class="btn btn-success" href="{{ route('attribute.edit', $attribute->id) }}"><i
                                    class="fa fa-edit"></i></a>
                            <a class="btn btn-warning" href="{{ route('attribute.delete', $attribute->id) }}"><i
                                    class="fa fa-trash-o"></i></a>
                        </div>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
{{ $attributes->links('pagination::bootstrap-4') }}
