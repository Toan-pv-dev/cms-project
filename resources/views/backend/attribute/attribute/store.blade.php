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
    $url = $config['method'] == 'create' ? route('attribute.store') : route('attribute.update', $attribute->id);
@endphp
<form action="{{ $url }}" class="box" method="post">
    @csrf

    {{-- <p>{{ $url }}</p> --}}
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-9">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Thông tin chung</h5>
                    </div>
                    <div class="ibox-content">
                        @include('backend.attribute.attribute.components.general')
                    </div>
                </div>
                @include('backend.dashboard.components.album')
                @include('backend.attribute.attribute.components.seo')
            </div>

            <div class="col-lg-3">
                @include('backend.attribute.attribute.components.aside')
            </div>
        </div>
        <div class="text-right mb10">
            <button class="btn btn-w-m btn-primary" type="submit" name="send" value="send">
                Lưu lại
            </button>
        </div>

        {{-- <div class="text-right mb10">
                <button class="btn btn-w-m btn-primary" type="submit" name="send" value="send">
                    Lưu lại
                </button>
            </div> --}}
    </div>
</form>
