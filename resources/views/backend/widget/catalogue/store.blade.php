@include('backend.dashboard.components.breadcrumb', ['title' => $config['seo']['create']['title']])
@if ($errors->any())
    <div class="alert alert-danger" role="alert">

        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </div>

@endif
@php
    $url =
        $config['method'] == 'create'
            ? route('user.catalogue.store')
            : route('user.catalogue.update', $user_catalogue->id);
@endphp
<form action="{{ $url }}" class="box" method="post">
    @csrf

    {{-- <p>{{ $url }}</p> --}}
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-5">
                <div class="panel-head">
                    <div class="panel-title">
                        Thông tin chung
                    </div>
                    <div class="panel-description">
                        <p>- Nhập thông tin nhóm thành viên</span>
                        <p>- Lưu ý: Những trường đánh dấu <span class="text-danger">(*)</span> la bat buoc</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="ibox">
                    <div class="ibox-content">
                        <div class="row ">
                            <div class="col-lg-6 mb10">
                                <div class="form-row">
                                    <label class="control-label text-left" for="">Tên nhóm</label>
                                    <span class="text-danger">(*)</span>
                                    <input type="text" name="name"
                                        value="{{ old('name', $user_catalogue->name ?? '') }}" class="form-control"
                                        placeholder="" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-lg-6 mb10">
                                <div class="form-row">
                                    <label class="control-label text-left" for="">Mô tả nhóm</label>
                                    <span class="text-danger">(*)</span>
                                    <input type="text" name="description"
                                        value="{{ old('description', $user_catalogue->description ?? '') }}"
                                        class="form-control" placeholder="" autocomplete="off">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr>

        <div class="text-right mb10">
            <button class="btn btn-w-m btn-primary" type="submit" name="send" value="send">
                Lưu lại
            </button>
        </div>
    </div>
</form>
