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

                    <select type="text" name="parent_id" value="{{ old('parent_id', $post->name ?? '') }}"
                        class="setupSelect2" class="form-control" placeholder="" autocomplete="off">
                        @foreach ($dropdown as $key => $item)
                            <option
                                {{ $key == old('parent_id', isset($post->parent_id) ? $post->parent_id : '') ? 'selected' : '' }}
                                value="{{ $key }}">{{ $item }}</option>
                        @endforeach

                        {{-- <option value="2">..</option> --}}
                    </select>
                </div>
            </div>

            <div class="col-lg-12 mb10">
                <div class="form-row">
                    <p><span class="text-danger notice">Thư mục phụ (*)</span>
                    </p>

                    <select name="post_catalogue_id[]" class="setupSelect2" class="form-control" placeholder=""
                        autocomplete="off" multiple="multiple">
                        @foreach ($dropdown as $key => $item)
                            <option @if (is_array(old('post_catalogue_id', isset($post->post_catalogue_id) ? $post->post_catalogue_id : [])) &&
                                    in_array($key, old('post_catalogue_id', isset($post->post_catalogue_id) ? $post->post_catalogue_id : []))) selected @endif value="{{ $key }}">
                                {{ $item }}

                            </option>
                            {{-- <option value="{{ $key }}" @if (in_array($key, old('catalogues', isset($post->catalogues) ? (is_array($post->catalogues) ? $post->catalogues : explode(',', $post->catalogues)) : []))) selected @endif>
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
                            src="{{ old('image') ?: $post->image ?? asset('backend/img/no_image.jpg') }}"
                            alt="Uploaded Image">
                        <input type="hidden" name="image" value="{{ old('image', $post->image ?? '') }}">
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
                                    {{ $key == old('publish', isset($post->publish) ? $post->publish : '') ? 'selected' : '' }}
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
                                    {{ $key == old('follow', isset($post->follow) ? $post->follow : '') ? 'selected' : '' }}
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
