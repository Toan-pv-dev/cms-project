@include('backend.dashboard.components.breadcrumb', [
    'title' =>
        $config['method'] == 'create' ? $config['seo']['create']['title'] : $config['seo']['update']['title'],
])
@if ($errors->any())
    <div class="alert alert-danger" role="alert">

        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </div>

@endif
@php
    $url = $config['method'] == 'create' ? route('widget.store') : route('widget.update', $widget->id);
@endphp
<form action="{{ $url }}" class="box" method="post">
    @csrf
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-9">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Thông tin widget</h5>
                    </div>
                    <div class="ibox-content">
                        @include('backend.dashboard.components.general', ['offTitle' => false])

                    </div>
                </div>
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Thông tin widget</h5>
                    </div>
                    <div class="ibox-content">
                        @include('backend.dashboard.components.album', ['offTitle' => false])

                    </div>
                </div>
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Cấu hình nội dung Widget</h5>
                    </div>
                    <div class="ibox-content model-wrapper">
                        <p class="text-danger">Chọn loại widget để cấu hình</p>
                        @foreach (__('module.model') as $key => $value)
                            <div class="model-item uk-flex uk-flex-middle">
                                <input type="radio" name="model" id="model-{{ $key }}"
                                    value="{{ $key }}"
                                    {{ old('model', $widget->model ?? '') == $key ? 'checked' : '' }} class="mr10">
                                <label for="model-{{ $key }}">{{ $value }}</label>
                            </div>
                        @endforeach
                        <div class="search-model-box mt20">
                            <input type="text" class="form-control search-model" placeholder="Tìm kiếm...">
                            <i class="fa fa-search"></i>
                        </div>



                        <div class="ajax-search-result hidden">
                        </div>
                        <div class="result-item-container"></div>

                    </div>
                </div>
            </div>
            @include('backend.widget.widget.components.aside', [
                'widget' => $widget ?? null,
                'config' => $config,
            ])
        </div>

    </div>
</form>
