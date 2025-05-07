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
                    <select name="attribute_catalogue_id" type="text" class="setupSelect2" class="form-control"
                        placeholder="" autocomplete="off">
                        @foreach ($dropdown as $key => $item)
                            <option
                                {{ $key == old('attribute_catalogue_id', $attribute->attribute_catalogue_id ?? '') ? 'selected' : '' }}
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

                    <select name="attribute_catalogue[]" class="setupSelect2" class="form-control" placeholder=""
                        autocomplete="off" multiple="multiple">
                        @foreach ($dropdown as $key => $item)
                            @if ($key != old('attribute_catalogue_id', $attribute->attribute_catalogue_id ?? ''))
                                <!-- Kiểm tra và loại bỏ attribute_catalogue_id khỏi danh sách -->
                                <option @if (is_array(old(
                                            'attribute_catalogue',
                                            isset($attribute_catalogue) && count($attribute_catalogue) ? $attribute_catalogue : [])) && in_array($key, old('attribute_catalogue', isset($attribute_catalogue) ? $attribute_catalogue : []))) selected @endif value="{{ $key }}">
                                    {{ $item }}
                                </option>
                            @endif
                            {{-- <option value="{{ $key }}" @if (in_array($key, old('catalogues', isset($attribute->catalogues) ? (is_array($attribute->catalogues) ? $attribute->catalogues : explode(',', $attribute->catalogues)) : []))) selected @endif>
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
        <h5>Chọn ảnh đại diện</h5>
    </div>
    <div class="ibox-content">
        <div class="row ">
            <div class="col-lg-12 mb10">
                <div class="form-row">

                    <span class="img img-cover">
                        <img class="img-target"
                            src="{{ old('image') ?: $attribute->image ?? asset('backend/img/no_image.jpg') }}"
                            alt="Uploaded Image">
                        <input type="hidden" name="image" value="{{ old('image', $attribute->image ?? '') }}">
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
                        <select class="form-control setupSelect2" name="publish" id="">[Chon nhom thanh vien]
                            @foreach (config('apps.general.publish') as $key => $item)
                                {
                                <option
                                    {{ $key == old('publish', isset($attribute->publish) ? $attribute->publish : '') ? 'selected' : '' }}
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
                                    {{ $key == old('follow', isset($attribute->follow) ? $attribute->follow : '') ? 'selected' : '' }}
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
