<div class="ibox">
    <div class="ibox-title">
        <h5>{{ __('messages.moduleName.parent_id') }}</h5>

    </div>
    <div class="ibox-content">
        <div class="row ">
            <div class="col-lg-12 mb10">
                <div class="form-row">
                    <p><span class="text-danger notice">{{ __('messages.moduleName.note-parent_id') }} </span>
                    </p>

                    <select type="text" name="parent_id" value="{{ old('parent_id', $moduleName->name ?? '') }}"
                        class="setupSelect2" class="form-control" placeholder="" autocomplete="off">
                        @foreach ($dropdown as $key => $item)
                            <option
                                {{ $key == old('parent_id', isset($moduleName->parent_id) ? $moduleName->parent_id : '') ? 'selected' : '' }}
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
        <h5>{{ __('messages.moduleName.note-post_image') }}</h5>
    </div>
    <div class="ibox-content">
        <div class="row ">
            <div class="col-lg-12 mb10">
                <div class="form-row">

                    <span class="img img-cover">
                        <img class="img-target"
                            src="{{ old('image') ?: $moduleName->image ?? asset('backend/img/no_image.jpg') }}"
                            alt="Uploaded Image">
                        <input type="hidden" name="image" value="{{ old('image', $moduleName->image ?? '') }}">
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
                        for="">{{ __('messages.moduleName.publish_status') }}</label>
                    <div class="user_option_form ">
                        <select class="form-control setupSelect2" name="publish" id="">
                            @foreach (config('apps.general.publish') as $key => $item)
                                {
                                <option
                                    {{ $key == old('publish', isset($moduleName->publish) ? $moduleName->publish : '') ? 'selected' : '' }}
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
                        for="">{{ __('messages.moduleName.follow_status') }}</label>
                    <div class="user_option_form ">
                        <select class="form-control setupSelect2" name="follow" id="follow">
                            @foreach (config('apps.general.follow') as $key => $item)
                                {
                                <option
                                    {{ $key == old('follow', isset($moduleName->follow) ? $moduleName->follow : '') ? 'selected' : '' }}
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
