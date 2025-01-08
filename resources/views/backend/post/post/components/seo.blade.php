<div class="ibox">
    <div class="ibox-title">
        <h5>CẦU HÌNH SEO</h5>
    </div>
    <div class="ibox-content">
        <div class="seo-container">
            <div class="meta-title">
                {{ old('meta_title', $post->meta_title ?? 'Bạn chưa có tiêu đề SEO') }}
            </div>
            <div class="canonical">
                {{ old('canonical', $post->canonical ?? '')
                    ? config('app.url') . old('canonical', $post->canonical ?? '') . config('apps.general.suffix')
                    : 'http://duong-dan-cua-bai.html' }}

            </div>
            <div class="meta_description">
                {{ old('meta_description', $post->meta_description ?? 'Bạn chưa nhập description') }}
            </div>
        </div>
        <div class="seo-wrapper">
            <div class="row mb15">
                <div class="col-lg-12">
                    <div class="form-row">
                        <div class="form-row">
                            <label for="" class="control-label text-left" style="width: 100%;">
                                <div class="uk-flex uk-flex-middle uk-flex-between">
                                    <span>Mô tả SEO</span>
                                    <span class="count_meta-title">0 ký tự </span>
                                </div>
                            </label>
                            <input type="text" name="meta_title"
                                value="{{ old('meta_title', $post->meta_title ?? '') }}" class="form-control"
                                placeholder="" autocomplete="off">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb15">
                <div class="col-lg-12">
                    <div class="form-row">
                        <div class="form-row">
                            <label for="" class="control-label text-left" style="width: 100%;">
                                <span>Từ khóa SEO</span>
                            </label>
                            <input type="text" name="meta_keyword"
                                value="{{ old('meta_keyword', $post->meta_keyword ?? '') }}" class="form-control"
                                placeholder="" autocomplete="off">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb15">
                <div class="col-lg-12">
                    <div class="form-row">
                        <div class="form-row">
                            <label for="" class="control-label text-left" style="width: 100%;">
                                <div class="uk-flex uk-flex-middle uk-flex-between">
                                    <span>Mô tả SEO</span>
                                    <span class="count_meta-title">0 ký tự </span>
                                </div>
                            </label>
                            <textarea type="text" name="meta_description" class="form-control meta_description-post-catalouge"
                                autocomplete="off">{{ trim(old('meta_description', $post->meta_description ?? '')) }}</textarea>

                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb15">
                <div class="col-lg-12">
                    <div class="form-row">
                        <div class="form-row">
                            <label for="" class="control-label text-left" style="width: 100%;">
                                <span>Đường dẫn</span>
                            </label>
                            <div class="input-container">
                                <input type="text" name="canonical"
                                    value="{{ old('meta_description', $post->canonical ?? '') }}" class="form-control "
                                    placeholder="" autocomplete="off">
                                <span class="baseUrl">{{ config('app.url') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
