<div class="ibox">
    <div class="ibox-title">
        <h5>{{ __('messages.productCatalogue.parent_id') }}</h5>

    </div>
    <div class="ibox-content">
        <div class="row ">
            <div class="col-lg-12 mb10">
                <div class="form-row">
                    <p><span class="text-danger notice">{{ __('messages.productCatalogue.note-parent_id') }} </span>
                    </p>

                    <select type="text" name="parent_id" value="{{ old('parent_id', $productCatalogue->name ?? '') }}"
                        class="setupSelect2" class="form-control" placeholder="" autocomplete="off">
                        @foreach ($dropdown as $key => $item)
                            <option
                                {{ $key == old('parent_id', isset($productCatalogue->parent_id) ? $productCatalogue->parent_id : '') ? 'selected' : '' }}
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
        <h5>{{ __('messages.productCatalogue.note-post_image') }}</h5>
    </div>
    <div class="ibox-content">
        <div class="row ">
            <div class="col-lg-12 mb10">
                <div class="form-row">

                    <span class="img img-cover">
                        <img class="img-target"
                            src="{{ old('image') ?: $productCatalogue->image ?? asset('backend/img/no_image.jpg') }}"
                            alt="Uploaded Image">
                        <input type="hidden" name="image" value="{{ old('image', $productCatalogue->image ?? '') }}">
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
                    <label class="control-label text-left "
                        for="">{{ __('messages.productCatalogue.publish_status') }}</label>
                    <div class="user_option_form ">
                        <select class="form-control setupSelect2" name="publish" id="">
                            @foreach (config('apps.general.publish') as $key => $item)
                                {
                                <option
                                    {{ $key == old('publish', isset($productCatalogue->publish) ? $productCatalogue->publish : '') ? 'selected' : '' }}
                                    value="{{ $key }}">{{ $item }}</option>
                                }
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 mb10">
                <div class="form-row">
                    <label class="control-label text-left "
                        for="">{{ __('messages.productCatalogue.follow_status') }}</label>
                    <div class="user_option_form ">
                        <select class="form-control setupSelect2" name="follow" id="follow">
                            @foreach (config('apps.general.follow') as $key => $item)
                                {
                                <option
                                    {{ $key == old('follow', isset($productCatalogue->follow) ? $productCatalogue->follow : '') ? 'selected' : '' }}
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
