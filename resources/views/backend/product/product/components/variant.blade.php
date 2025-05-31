@php
    if (isset($product)) {
        $variantValues = json_decode($product->variants);
    }
@endphp
<div class="ibox variant-box">
    <div class="ibox-title">
        <h5>Sản phẩm có nhiều phiên bản</h5>
        <div class="description">
            Cho phep ban babn cac phien ban khac nhau cua san pham, vi du: quan, ao thi co cac <strong
                class="text-danger">mau sac</strong> va <strong class="text-danger">size</strong> so khac nhau. Moi phien
            ban la 1 dong danh muc trong danh sach phien ban phia duoi
        </div>
    </div>
    <div class="ibox-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="variant-checkbox " style="display: flex; align-items: center;">
                    <input type="checkbox" name="variant_checkbox" id="variant" class="variant_checkbox "
                        value="1" style="margin-right: 5px;"
                        {{ old('variant_checkbox') || (isset($product) ? $product->product_variants : '') ? 'checked' : '' }}>
                    <label for="variant" class="uk-form-label" style="margin: 0;">Cho phép tạo nhiều phiên bản cho sản
                        phẩm này</label>
                </div>
            </div>
        </div>
        @php
            $attributesOld = old('attributes', []);
            $attributeCatalogueIds = old(
                'attribute_catalogue_id',
                isset($product->attributeCatalogue) ? json_decode($product->attributeCatalogue, true) : [],
            );
            if (isset($product)) {
                $attributeData = json_decode($product->attributes, true);
            }

            // Lấy dữ liệu attribute catalogue từ database
            $existingVariantItems = [];
            if (isset($product) && $product->exists && !empty($product->attributeCatalogue)) {
                $attributeCatalogueData = json_decode($product->attributeCatalogue, true);
                if (is_array($attributeCatalogueData)) {
                    foreach ($attributeCatalogueData as $index => $catalogueId) {
                        $existingVariantItems[$index] = [
                            'catalogue_id' => $catalogueId,
                            'values' => $attributeData[$index] ?? [],
                        ];
                    }
                }
            }
            // Ưu tiên: old data -> old attribute_catalogue_id -> existing data -> empty array
            if (!empty($attributesOld)) {
                $variantItems = $attributesOld;
            } elseif (!empty($existingVariantItems)) {
                $variantItems = $existingVariantItems;
            } else {
                $variantItems = [];
            }
        @endphp
        <div
            class="variant-wrapper {{ old('variant_checkbox') || count($product->product_variants ?? []) ? '' : 'hidden' }}">
            <div class="row variant-container">
                <div class="col-md-3 col-12">
                    <div class="attribute-title mb1010">
                        Chon thuoc tinh
                    </div>
                </div>
                <div class="col-md-9 col-12">
                    <div class="attribute-title mb10">Chon gia tri thuoc tinh</div>
                </div>
            </div>

            <div class="variant-body">
                @if (!empty($variantItems))
                    @foreach ($variantItems as $index => $item)
                        @php
                            $selectedId = $item['catalogue_id'] ?? ($item['attribute_catalogue_id'] ?? '');
                            $selectedValues =
                                $item['values'] ?? ($item['attribute_values'] ?? ($item['attribute_id'] ?? []));
                            print_r($selectedValues);
                        @endphp
                        <div class="row variant-item {{ $index > 0 ? 'mt10' : '' }}">
                            <div class="col-md-3 col-12">
                                <div class="attribute-catalogue">
                                    <select name="attribute_catalogue_id[{{ $index }}]"
                                        class="attribute-select nice-select form-select choose-attribute">
                                        <option value="">Chọn nhóm thuộc tính</option>
                                        @foreach ($attributeCatalogues as $catalogue)
                                            @php
                                                $lang = $catalogue->attribute_catalogue_language->first();
                                            @endphp
                                            @if ($lang)
                                                <option value="{{ $catalogue->id }}"
                                                    {{ $selectedId == $catalogue->id ? 'selected' : '' }}>
                                                    {{ $lang->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-8 col-10">
                                <input type="hidden" name="attributes[{{ $index }}][catalogue_id]"
                                    value="{{ $selectedId }}">
                                <select class = "selectVariant form-control variant-{{ $selectedId }} " multiple
                                    name="attributes[{{ $index }}][values][]"
                                    data-catid="{{ $selectedId }}"></select>
                            </div>

                            <div class="col-md-1 col-2 variant-delete text-end">
                                <button type="button" class="remove-attribute btn btn-danger"><i
                                        class="fa fa-trash"></i></button>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="row variant-item">
                        <div class="col-md-3 col-12">
                            <div class="attribute-catalogue">
                                <select name="attribute_catalogue_id[]"
                                    class="attribute-select nice-select form-select choose-attribute">
                                    <option value="">Chọn nhóm thuộc tính</option>
                                    @foreach ($attributeCatalogues as $catalogue)
                                        @php
                                            $lang = $catalogue->attribute_catalogue_language->first();
                                        @endphp
                                        @if ($lang)
                                            <option value="{{ $catalogue->id }}">{{ $lang->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-8 col-10">
                            <input type="text" name="fake_variant[]" class="fake-variant form-control" disabled>
                        </div>
                        <div class="col-md-1 col-2 variant-delete text-end">
                            <button type="button" class="remove-attribute btn btn-danger"><i
                                    class="fa fa-trash"></i></button>
                        </div>
                    </div>
                @endif
            </div>


            <div class="variant-foot mt10">
                <button type="button" class="add-variant">Them moi phien ban</button>
            </div>
        </div>
    </div>

</div>

<div class="ibox product-variant">
    <div class="ibox-title">
        <h5>Danh sach phien ban</h5>
    </div>
    <div class="ibox-content">
        <div class="table-responsive">
            <table class="table table-striped variantTable">
                <thead>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        <template id="variant-attribute-template">
            <tr class="updateVariantRow">
                <td colspan="6">
                    <div class="updateVariant ibox">
                        <div class="ibox-title">
                            <div class="uk-flex uk-flex-middle uk-flex-between">
                                <h5>Cap nhat thong tin phien ban</h5>
                                <div class="button-group">
                                    <div class="uk-flex uk flex-middle">
                                        <button type="button" class="cancleUpdate btn btn-danger mr10">Cancel</button>
                                        <button type="button" class="saveUpdate tbn btn-success">Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="ibox-content ">
                            @php
                                $album = $album ?? [];
                                $isAlbumEmpty = empty($album);
                            @endphp

                            {{-- Phần hiển thị nút chọn hình khi album trống --}}
                            <div class="click-to-upload-variant {{ !old('album') && $isAlbumEmpty ? '' : 'hidden' }}">
                                <div class="icon">
                                    <a href="" class="upload-picture-variant">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80"
                                            viewBox="0 0 24 24">
                                            <path
                                                d="M9 12c0-.552.448-1 1.001-1s.999.448.999 1-.446 1-.999 1-1.001-.448-1.001-1zm6.2 0l-1.7 2.6-1.3-1.6-3.2 4h10l-3.8-5zm5.8-7v-2h-21v15h2v-13h19zm3 2v14h-20v-14h20zm-2 2h-16v10h16v-10z"
                                                fill="#ced9d2" />
                                        </svg>
                                    </a>
                                </div>
                                <div class="small-text">
                                    Sử dụng nút chọn hình hoặc click vào đây để thêm hình ảnh
                                </div>
                            </div>

                            {{-- Phần hiển thị danh sách ảnh --}}
                            <div class="upload-list-varia nt">
                                <ul id="sortable2" class="clearfix data-album sortui ui-sortable">
                                    @foreach (old('album', $album) as $key => $val)
                                        @php
                                            $imageUrl = is_object($val) ? $val->url ?? '' : $val;
                                        @endphp
                                        <li class="ui-state-default">
                                            <div class="thumb">
                                                <span class="span image img-scaledown">
                                                    <img src="{{ $imageUrl }}" alt="{{ $imageUrl }}">
                                                    <input type="hidden" name="variantAlbum[]"
                                                        value="{{ $imageUrl }}">
                                                </span>
                                                <button class="delete-image">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>
                                        </li>
                                    @endforeach

                                </ul>
                            </div>
                            @if (!empty($variantItems))
                                <div class="second-content">
                                    <div class="row uk-flex uk-flex-middle mt20">
                                        <div class="col-lg-2">
                                            <label class="mr10" for="">Quản lý tồn kho</label>
                                            <input type="checkbox" class="js-switch allowQuantity"
                                                data-target="variantQuantity"
                                                {{ $variantValues[$index]->quantity ?? old('variants.' . $index . '.quantity') ? 'checked' : '' }}>
                                        </div>
                                        <div class="col-lg-10">
                                            <div class="row">
                                                <div class="col-lg-3">
                                                    <label for="" class="control-lavel">Số lượng</label>
                                                    <input type="text" id="variantQuantity" name="td-quantity"
                                                        class="form-control{{ !old('variants.' . $index . '.quantity') ? ' isDisabled' : '' }}"
                                                        value="{{ old('variants.' . $index . '.quantity') }}"
                                                        {{ !old('variants.' . $index . '.quantity') ? 'disabled' : '' }}>
                                                </div>
                                                <div class="col-lg-3">
                                                    <label for="" class="control-lavel">SKU</label>
                                                    <input type="text" id="variantQuantity" name="td-sku"
                                                        class="form-control">
                                                </div>
                                                <div class="col-lg-3">
                                                    <label for="" class="control-lavel">Giá</label>
                                                    <input type="text" id="variantPrice" class="form-control"
                                                        name="td-price">
                                                </div>
                                                <div class="col-lg-3">
                                                    <label for="" class="control-lavel">Barcode</label>
                                                    <input type="text" id="VariantBarcode" class="form-control"
                                                        name="td-barcode">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row uk-flex uk-flex-middle mt20">
                                        <div class="col-lg-2">
                                            <label class="mr10" for="">Quản lý File</label>
                                            <input type="checkbox" name="" class="js-switch allowQuantity"
                                                data-target="variantQuantity">
                                        </div>
                                        <div class="col-lg-10">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <label for="" class="control-label">Tên File</label>
                                                    <input type="text" id="fileName" disabled
                                                        name="variant-file-name" class="form-control isDisabled">
                                                </div>
                                                <div class="col-lg-6">
                                                    <label for="" class="control-label">Đường dẫn</label>
                                                    <input type="text" id="path" name="variant-path"
                                                        class="form-control isDisabled" disabled>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="second-content">
                                    <div class="row uk-flex uk-flex-middle mt20">
                                        <div class="col-lg-2">
                                            <label class="mr10" for="">Quản lý tồn kho</label>
                                            <input type="checkbox" class="js-switch allowQuantity"
                                                data-target="variantQuantity">
                                        </div>
                                        <div class="col-lg-10">
                                            <div class="row">
                                                <div class="col-lg-3">
                                                    <label for="" class="control-lavel">Số lượng</label>
                                                    <input type="text" id="variantQuantity" disabled
                                                        name="td-quantity" class="form-control isDisabled">
                                                </div>
                                                <div class="col-lg-3">
                                                    <label for="" class="control-lavel">SKU</label>
                                                    <input type="text" id="variantQuantity" name="td-sku"
                                                        class="form-control">
                                                </div>
                                                <div class="col-lg-3">
                                                    <label for="" class="control-lavel">Giá</label>
                                                    <input type="text" id="variantPrice" class="form-control"
                                                        name="td-price">
                                                </div>
                                                <div class="col-lg-3">
                                                    <label for="" class="control-lavel">Barcode</label>
                                                    <input type="text" id="VariantBarcode" class="form-control"
                                                        name="td-barcode">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row uk-flex uk-flex-middle mt20">
                                        <div class="col-lg-2">
                                            <label class="mr10" for="">Quản lý File</label>
                                            <input type="checkbox" name="" class="js-switch allowQuantity"
                                                data-target="variantQuantity">
                                        </div>
                                        <div class="col-lg-10">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <label for="" class="control-label">Tên File</label>
                                                    <input type="text" id="fileName" disabled
                                                        name="variant-file-name" class="form-control isDisabled">
                                                </div>
                                                <div class="col-lg-6">
                                                    <label for="" class="control-label">Đường dẫn</label>
                                                    <input type="text" id="path" name="variant-path"
                                                        class="form-control isDisabled" disabled>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </td>
            </tr>
        </template>
    </div>

</div>

<pre>{{ print_r(old('attributes'), true) }}</pre>
<pre>{{ print_r(old('variants'), true) }}</pre>

<script>
    var attributeCatalogue = @json(
        $attributeCatalogues->map(function ($item) {
            $name = $item->attribute_catalogue_language->first()->name;
            return [
                'id' => $item->id,
                'name' => $name,
            ];
        }));

    var rawVariants = {!! json_encode(old('variants') ?? ($product->variants ?? '')) !!};
    var oldVariants = typeof rawVariants === 'string' ? JSON.parse(rawVariants) : rawVariants;



    var attribute = {!! json_encode(old('attributes') ?? $existingVariantItems) !!};
    console.log('attribute:', attribute);
</script>
