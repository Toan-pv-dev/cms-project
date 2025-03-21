<div class="row mb15">
    <div class="col-lg-6">
        <div class="form-row">
            <label for="" class="control-label text-left">Tiêu đề nhóm bài viết</label>
            <span class="text-danger">(*)</span>
            <input type="text" name="translate_name" value="{{ old('translate_name', $modelTranslate->name ?? '') }}"
                class="form-control" placeholder="" autocomplete="off">
        </div>
    </div>
</div>
<div class="row mb15">
    <div class="col-lg-12">
        <div class="form-row">
            <label for="" class="control-label text-left">Mô tả ngắn</label>
            <span class="text-danger">(*)</span>
            <textarea type="text" name="translate_description" class="form-control ck-editor" placeholder="" autocomplete="off"
                id="translate_description" data-height="500">{{ old('translate_description', isset($modelTranslate->description) ? $modelTranslate->description : '') }}</textarea>
        </div>
    </div>
</div>
<div class="row mb15">
    <div class="col-lg-12">
        <div class="form-row">
            <div class="uk-flex uk-flex-middle uk-flex-between">

                <label for="" class="control-label text-left">Nội dung</label>
                <a href="" class="multipleUploadImageCkeditor" data-target="content"> Upload nhiều hình ảnh</a>
            </div>

            <textarea type="text" name="translate_content" class="form-control ck-editor" placeholder="" autocomplete="off"
                id="translate_content" data-height="500">{{ old('translate_content', $modelTranslate->content ?? '') }}</textarea>
        </div>

    </div>
</div>
