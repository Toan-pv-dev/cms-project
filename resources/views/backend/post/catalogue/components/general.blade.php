<div class="row mb15">
    <div class="col-lg-12">
        <div class="form-row">
            <label for="" class="control-label text-left">Tiêu đề nhóm bài viết</label>
            <span class="text-danger">(*)</span>
            <input type="text" name="name" value="{{ old('name', $postCatalogue->name ?? '') }}" class="form-control"
                placeholder="" autocomplete="off">
        </div>
    </div>
</div>
<div class="row mb15">
    <div class="col-lg-12">
        <div class="form-row">
            <label for="" class="control-label text-left">Mô tả ngắn</label>
            <span class="text-danger">(*)</span>
            <textarea type="text" name="description" class="form-control ck-editor" placeholder="" autocomplete="off"
                id="description" data-height="500">{{ old('description', isset($postCatalogue->description) ? $postCatalogue->description : '') }}</textarea>
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

            <textarea type="text" name="content" class="form-control ck-editor" placeholder="" autocomplete="off" id="content"
                data-height="500">{{ old('content', $postCatalogue->content ?? '') }}</textarea>
        </div>

    </div>
</div>
