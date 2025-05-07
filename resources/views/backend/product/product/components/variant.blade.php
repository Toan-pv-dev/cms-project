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
                    <input type="checkbox" name="" id="variant" class="variant_checkbox " value="1"
                        style="margin-right: 5px;">
                    <label for="variant" class="uk-form-label" style="margin: 0;">Cho phép tạo nhiều phiên bản cho sản
                        phẩm này</label>
                </div>
            </div>
        </div>
        <div class="variant-wrapper hidden">
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
                <div class="row variant-item">
                    <div class="col-md-3 col-12">
                        <div class="attribute-catalogue">
                            <select name="" id=""
                                class="attribute-select nice-select form-select choose-attribute">
                                <option value="0">Chon nhom thuoc tinhh</option>
                                @foreach ($attributeCatalogues as $catalogue)
                                    @php
                                        $lang = $catalogue->attribute_catalogue_language->first(); // hoặc ->where('language_id', 1)->first()
                                    @endphp
                                    @if ($lang)
                                        <option value="{{ $catalogue->id }}">{{ $lang->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-8 col-10">
                        <input type="text" name="" class="fake-variant form-control" disabled>
                    </div>
                    <div class="col-md-1 col-2 variant-delete text-end">
                        <button type="button" class="remove-attribute btn btn-danger"><i
                                class="fa fa-trash"></i></button>
                    </div>
                </div>
            </div>
            <div class="variant-foot mt10">
                <button type="button" class="add-variant">Them moi phien ban</button>
            </div>
        </div>
    </div>
</div>

<script>
    var attributeCatalogue = @json(
        $attributeCatalogues->map(function ($item) {
            $name = $item->attribute_catalogue_language->first()->name;
            return [
                'id' => $item->id,
                'name' => $name,
            ];
        }));
</script>
