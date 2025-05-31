<div class="ibox">
    <div class="ibox-title">
        <h5>Chọn thư mục cha</h5>

    </div>
    <div class="ibox-content">
        <div class="row ">
            <div class="col-lg-12 mb10">
                <div class="form-row">
                    <p><span class="text-danger notice">Chọn Root nêú không tồn tại thư mục cha (*)</span>
                    </p>
                    <select name="product_catalogue_id" type="text" class="setupSelect2" class="form-control"
                        placeholder="" autocomplete="off">
                        @foreach ($dropdown as $key => $item)
                            <option
                                {{ $key == old('product_catalogue_id', $product->product_catalogue_id ?? '') ? 'selected' : '' }}
                                value="{{ $key }}">{{ $item }}
                            </option>
                        @endforeach

                        {{-- <option value="2">..</option> --}}
                    </select>
                </div>
            </div>

            <div class="col-lg-12 mb10">
                <div class="form-row">
                    <p><span class="text-danger notice">Thư mục phụ (*)</span>
                    </p>

                    <select name="product_catalogue[]" class="setupSelect2" class="form-control" placeholder=""
                        autocomplete="off" multiple="multiple">
                        @foreach ($dropdown as $key => $item)
                            <!-- Kiểm tra và loại bỏ product_catalogue_id khỏi danh sách -->
                            <option @if (is_array(old('product_catalogue', isset($product_catalogue) && count($product_catalogue) ? $product_catalogue : [])) && in_array($key, old('product_catalogue', isset($product_catalogue) ? $product_catalogue : []))) selected @endif value="{{ $key }}">
                                {{ $item }}
                            </option>
                            {{-- <option value="{{ $key }}" @if (in_array($key, old('catalogues', isset($product->catalogues) ? (is_array($product->catalogues) ? $product->catalogues : explode(',', $product->catalogues)) : []))) selected @endif>
                                {{ $item }}
                            </option> --}}
                        @endforeach

                        {{-- <option value="2">..</option> --}}
                    </select>
                </div>
            </div>
        </div>


    </div>
</div>

<div class="ibox">
    <div class="ibox-title">
        <h5>Thông tin chung</h5>

    </div>
    <div class="ibox-content">
        <div class="row ">

            <div class="col-lg-12 mb10">
                <div class="form-row">
                    <label for="product_code">Mã sản phẩm</label>
                    <input type="text" id="product_code" name="product_code" class="form-control"
                        placeholder="Nhập mã sản phẩm" autocomplete="off"
                        value="{{ \Carbon\Carbon::now()->format('YmdHis') }}">
                </div>
            </div>
            <div class="col-lg-12 mb10">
                <div class="form-row">
                    <label for="made_in">Nơi sản xuất</label>
                    <input type="text" id="made_in" name="made_in" class="form-control"
                        placeholder="Nhập nơi sản xuất" autocomplete="off"
                        value="{{ old('made_in', $product->made_in ?? '') }}">
                </div>
            </div>
            <div class="col-lg-12 mb10">
                <div class="form-row">
                    <label for="product_price">Giá sản phẩm</label>
                    <input type="text" id="product_price" name="product_price" class="form-control"
                        placeholder="Nhập giá sản phẩm" autocomplete="off" value="100.000">
                </div>
            </div>


        </div>
    </div>


    <div class="ibox">
        <div class="ibox-title">
            <h5>Chọn ảnh đại diện</h5>
        </div>
        <div class="ibox-content">
            <div class="row ">
                <div class="col-lg-12 mb10">
                    <div class="form-row">

                        <span class="img img-cover">
                            <img class="img-target"
                                src="{{ old('image') ?: $product->image ?? asset('backend/img/no_image.jpg') }}"
                                alt="Uploaded Image">
                            <input type="hidden" name="image" value="{{ old('image', $product->image ?? '') }}">
                        </span>
                    </div>

                </div>
            </div>


        </div>
    </div>

    <div class="ibox">
        <div class="ibox-content">
            <div class="row ">
                <div class="col-lg-12 mb10">
                    <div class="form-row">
                        <label class="control-label text-left " for="">Nhóm thành viên</label>
                        <div class="user_option_form ">
                            <select class="form-control setupSelect2" name="publish" id="">[Chon nhom thanh
                                vien]
                                @foreach (config('apps.general.publish') as $key => $item)
                                    {
                                    <option
                                        {{ $key == old('publish', isset($product->publish) ? $product->publish : '') ? 'selected' : '' }}
                                        value="{{ $key }}">{{ $item }}</option>
                                    }
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12 mb10">
                    <div class="form-row">
                        <label class="control-label text-left " for="">Nhóm thành viên</label>
                        <div class="user_option_form ">
                            <select class="form-control setupSelect2" name="follow" id="follow">[Chon nhom
                                thanh vien]
                                @foreach (config('apps.general.follow') as $key => $item)
                                    {
                                    <option
                                        {{ $key == old('follow', isset($product->follow) ? $product->follow : '') ? 'selected' : '' }}
                                        value="{{ $key }}">{{ $item }}</option>
                                    }
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

            </div>


        </div>
    </div>
