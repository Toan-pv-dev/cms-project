<div class="ibox">
    <div class="ibox-content">
        <div class="row ">
            <div class="col-lg-12 mb10">
                <div class="form-row">
                    <label class="control-label text-left" for="">Chọn danh mục cha <span
                            class="text-danger">(*)</span></label><br>
                    <p><span class="text-danger notice">Chọn Root nêú không tồn tại thư mục cha</span>
                    </p>

                    <select type="text" name="parent_id" value="{{ old('parent_id', $postCatalogue->name ?? '') }}"
                        class="setupSelect2" class="form-control" placeholder="" autocomplete="off">
                        @foreach ($dropdown as $key => $item)
                            <option
                                {{ $key == old('parent_id', isset($postCatalogue->parent_id) ? $postCatalogue->parent_id : '') ? 'selected' : '' }}
                                value="{{ $key }}">{{ $item }}</option>
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
                            src="{{ old('image') ?: $postCatalogue->image ?? asset('backend/img/no_image.jpg') }}"
                            alt="Uploaded Image">
                        <input type="hidden" name="image" value="{{ old('image', $postCatalogue->image ?? '') }}">
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
                                    {{ $key == old('publish', isset($postCatalogue->publish) ? $postCatalogue->publish : '') ? 'selected' : '' }}
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
                                    {{ $key == old('follow', isset($postCatalogue->follow) ? $postCatalogue->follow : '') ? 'selected' : '' }}
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
