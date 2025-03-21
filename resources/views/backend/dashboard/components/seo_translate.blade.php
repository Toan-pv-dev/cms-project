<div class="ibox">
    <div class="ibox-title">
        <h5>CẦU HÌNH SEO</h5>
    </div>
    {{-- @dd($modelTranslate) --}}

    <div class="ibox-content">
        <div class="seo-container">
            <div class="meta-title" name="translate_meta_title">
                {{ old('translate_meta_title', $modelTranslate->meta_title ?? 'Bạn chưa có tiêu đề SEO') }}
            </div>
            <div class="canonical" name="translate_canonical">
                {{ old('translate_canonical', $modelTranslate->canonical ?? '')
                    ? config('app.url') . old('canonical', $modelTranslate->canonical ?? '') . config('apps.general.suffix')
                    : 'http://duong-dan-cua-bai.html' }}

            </div>
            <div class="translate_meta_description">
                {{ old('translate_meta_description', $modelTranslate->meta_description ?? 'Bạn chưa nhập description') }}
            </div>
        </div>
        <div class="seo-wrapper">
            <div class="row mb15 ">
                <div class="col-lg-12">
                    <div class="form-row">
                        <div class="form-row">
                            <label for="" class="control-label text-left" style="width: 100%;">
                                <div class="uk-flex uk-flex-middle uk-flex-between">
                                    <span>Mô tả SEO</span>
                                    <span class="count_meta-title">0 ký tự </span>
                                </div>
                            </label>
                            <input type="text" name="translate_meta_title"
                                value="{{ old('translate_meta_title', $modelTranslate->meta_title ?? '') }}"
                                class="form-control" placeholder="" autocomplete="off" {{-- - --}}>
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
                            <input type="text" name="translate_meta_keyword"
                                value="{{ old('translate_meta_keyword', $modelTranslate->meta_keyword ?? '') }}"
                                class="form-control" placeholder="" autocomplete="off">
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
                            {{-- {{ $modelTranslate->meta_description }} --}}
                            <textarea type="text" name="translate_meta_description" class="form-control meta_description-post-catalouge">{{ old('meta_description', $modelTranslate->meta_description ?? '') }}</textarea>

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
                                <input type="text" name="translate_canonical"
                                    value="{{ old('translate_canonical', $modelTranslate->canonical ?? '') }}"
                                    class="form-control seo-canonical" placeholder="" autocomplete="off">
                                <span class="baseUrl">{{ config('app.url') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
