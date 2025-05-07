@include('backend.dashboard.components.breadcrumb', ['title' => $config['seo']['create']['title']])
@if ($errors->any())
    <div class="alert alert-danger" role="alert">

        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </div>

@endif
@php
    $url = $config['method'] == 'create' ? route('generate.store') : route('generate.update', $generate->id);
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
                                    <label class="control-label text-left" for="">Tên Model</label>
                                    <span class="text-danger">(*)</span>
                                    <input type="text" name="name"
                                        value="{{ old('name', $generate->name ?? '') }}" class="form-control"
                                        placeholder="" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-lg-6 mb10">
                                <div class="form-row">
                                    <label class="control-label text-left" for="">Tên chức năng</label>
                                    <span class="text-danger">(*)</span>
                                    <input type="text" name="module_name"
                                        value="{{ old('module_name', $generate->module_name ?? '') }}"
                                        class="form-control" placeholder="" autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="row ">
                            <div class="col-lg-6 mb10">
                                <div class="form-row">
                                    <label class="control-label text-left" for="">Loại module</label>
                                    <span class="text-danger">(*)</span>
                                    <select class="form-control setupSelect2" name="module_type" id="">
                                        <option value="0">Chon loai module</option>
                                        <option value="catalogue">Module danh muc</option>
                                        <option value="detail">Module chi tiet</option>
                                        <option value="difference">Module khac</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6 mb10">
                                <div class="form-row">
                                    <label class="control-label text-left" for="">Đường dẫn</label>
                                    <span class="text-danger">(*)</span>
                                    <input type="text" name="path"
                                        value="{{ old('path', $generate->path ?? '') }}" class="form-control"
                                        placeholder="" autocomplete="off">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-5">
                <div class="panel-head">
                    <div class="panel-title">
                        Thông tin Schema
                    </div>
                    <div class="panel-description">
                        <p>- Nhập thông tin Shema</span>
                        <p>- Lưu ý: Những trường đánh dấu <span class="text-danger">(*)</span> la bat buoc</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="ibox">
                    <div class="ibox-content">
                        <div class="row ">
                            <div class="col-lg-12 mb10">
                                <div class="form-row">
                                    <label class="control-label text-left" for="">Schema</label>
                                    <span class="text-danger">(*)</span>
                                    <textarea name="schema" value="{{ old('schema', $generate->schema ?? '') }}" class="form-control schema" placeholder=""
                                        autocomplete="off"></textarea>
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
