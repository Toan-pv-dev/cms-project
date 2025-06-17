<div class="col-lg-3">
    <div class="ibox">
        <div class="ibox-title">
            <h5>CÀI ĐẶT CƠ BẢN</h5>
        </div>
        <div class="ibox-content">
            <div class="row ">
                <div class=" mb10">
                    <div class="form-row m10 slide-input-item">
                        <label class="control-label text-left" for="">Tên slide</label>
                        <span class="text-danger">(*)</span>
                        <input type="text" name="name" value="{{ old('name', $slide->name ?? '') }}"
                            class="form-control" placeholder="" autocomplete="off">
                    </div>
                </div>
                <div class="mb10">
                    <div class="form-row m10 slide-input-item">
                        <label class="control-label text-left" for="">Từ khóa</label>
                        <span class="text-danger">(*)</span>
                        <input type="text" name="keyword" value="{{ old('keyword', $slide->keyword ?? '') }}"
                            class="form-control" placeholder="" autocomplete="off">
                    </div>
                </div>
                <div class="mb10 input-with-label slide-input-item">
                    <div class="form-group m10 uk-flex uk-flex-middle ">
                        <label class="control-label  col-lg-4" for="width">Chiều rộng</label>
                        <div class="input-group col-lg-8">
                            <input type="text" id="width" name="setting[width]"
                                value="{{ old('setting.width', $slide->setting['width'] ?? '') }}" class="form-control"
                                placeholder="" autocomplete="off">
                            <span class="input-group-addon">px</span>
                        </div>
                    </div>
                </div>
                <div class="mb10 input-with-label slide-input-item">
                    <div class="form-group m10 uk-flex uk-flex-middle ">
                        <label class="control-label col-lg-4" for="height">Chiều cao</label>
                        <div class="input-group col-lg-8">
                            <input type="text" id="height" name="setting[height]"
                                value="{{ old('setting.height', $slide->setting['height'] ?? '') }}"
                                class="form-control" placeholder="" autocomplete="off">
                            <span class="input-group-addon">px</span>
                        </div>
                    </div>
                </div>
                <div class="mb10 input-with-label slide-input-item">
                    <div class="form-group m10 uk-flex uk-flex-middle ">
                        <label class="control-label  col-lg-4" for="height">Hiệu ứng</label>
                        <div class="input-group col-lg-8">
                            <select class="form-control" name="setting[animation]" id="">
                                @foreach (__('module.effect') as $key => $value)
                                    <option value="{{ $key }}"
                                        {{ old('setting.animation', $slide->setting['animation'] ?? '') == $key ? 'selected' : '' }}>
                                        {{ $value }}</option>
                                @endforeach

                            </select>
                        </div>
                    </div>
                </div>
                <div class="mb10 input-with-label slide-input-item">
                    <div class="form-group m10 uk-flex uk-flex-middle ">
                        <label class="control-label  col-lg-4" for="arrow-checkbox">Mũi tên</label>
                        <div class="input-group col-lg-1">
                            <input type="checkbox" id="arrow-checkbox" name="setting[arrow]" value="accept"
                                class="form-control" placeholder="" autocomplete="off"
                                {{ old('setting.arrow', $slide->setting['arrow'] ?? '') ? 'checked' : '' }}>
                        </div>
                    </div>
                </div>
                <div class="mb10 input-with-label slide-input-item ">
                    <div class="form-group m10 uk-flex uk-flex-middle ">
                        <label class="control-label  col-lg-4" for="arrow-checkbox">Điều hướng</label>
                        <div class="input-group col-lg-8">
                            @foreach (__('module.navigation') as $key => $value)
                                <div class="form-check ">
                                    <input type="radio" value="{{ $key }}" class="form-check-input pl8"
                                        id="setting-{{ $key }}" name="setting[navigation]"
                                        {{ old('setting.navigation', $slide->setting['navigation'] ?? '') == $key ? 'checked' : '' }}>
                                    <label for="setting-{{ $key }}">{{ $value }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="ibox">
        <div class="ibox-title">
            <h5>CÀI ĐẶT NÂNG CAO</h5>
        </div>
        <div class="ibox-content">
            <div class="row ">
                <div class="mb10 input-with-label slide-input-item">
                    <div class="form-group m10 uk-flex uk-flex-middle ">
                        <label class="control-label  col-lg-4" for="arrow-checkbox">Tự động
                            chạy</label>
                        <div class="input-group col-lg-1">
                            <input type="checkbox" id="arrow-checkbox" name="setting[autoplay]" value="accepted"
                                class="form-control" placeholder="" autocomplete="off"
                                {{ old('setting.autoplay', $slide->setting['autoplay'] ?? '') ? 'checked' : '' }}>
                        </div>
                    </div>
                </div>
                <div class="mb10 input-with-label slide-input-item">
                    <div class="form-group m10 uk-flex uk-flex-middle ">
                        <label class="control-label  col-lg-4" for="arrow-checkbox">Dừng khi<br> di
                            chuột</label>
                        <div class="input-group col-lg-1">
                            <input type="checkbox" id="arrow-checkbox" name="setting[stop_on_hover]"
                                value="accepted"
                                {{ old('setting.stop_on_hover', $slide->setting['stop_on_hover'] ?? '') ? 'checked' : '' }}
                                class="form-control" placeholder="" autocomplete="off">
                        </div>
                    </div>
                </div>
                <div class="mb10 input-with-label slide-input-item">
                    <div class="form-group m10 uk-flex uk-flex-middle ">
                        <label class="control-label  col-lg-4" for="next_image">Chuyển ảnh</label>
                        <div class="input-group col-lg-8">
                            <input type="text" id="next_image" name="setting[next_image]"
                                value="{{ old('setting.next_image', $slide->setting['next_image'] ?? '') }}"
                                class="form-control" placeholder="" autocomplete="off">
                            <span class="input-group-addon">ms</span>
                        </div>
                    </div>
                </div>
                <div class="mb10 input-with-label slide-input-item">
                    <div class="form-group m10  uk-flex uk-flex-middle uk-flex-between ">
                        <label class="control-label  col-lg-3" for="animiation-speed">Tốc độ hiệu ứng</label>
                        <div class="input-group col-lg-8">
                            <input type="text" id="animiation-speed" name="setting[animation_speed]"
                                value="{{ old('setting.animation_speed', $slide->setting['animation_speed'] ?? '') }}"
                                class="form-control" placeholder="" autocomplete="off">
                            <span class="input-group-addon">ms</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="ibox">
        <div class="ibox-title">
            <h5>Short Code</h5>
        </div>
        <div class="ibox-content">
            <div class="row ">
                <div class="mb10 input-with-label slide-input-item">
                    <div class="form-group col-lg-12">
                        <textarea name="short_code" class="form-control text-area-shortcode " placeholder="" autocomplete="off">{{ old('short_code', $slide->short_code ?? '') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="text-right mb10">
        <button class="btn btn-w-m btn-primary" type="submit" name="send" value="send">
            Lưu lại
        </button>
    </div>
</div>
{{-- <pre>{{ json_encode(old('setting'), JSON_PRETTY_PRINT) }}</pre> --}}
