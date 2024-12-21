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
    $url = $config['method'] == 'create' ? route('user.store') : route('user.update', $user->id);
@endphp
<form action="{{ $url }}" class="box" method="post">
    @csrf
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-5">
                <div class="panel-head">
                    <div class="panel-title">
                        Thong tin chung
                    </div>
                    <div class="panel-description">
                        <p>- Nhap thong tin chung cua nguoi su dung</span>
                        <p>- Luu y: Nhung truong hop danh dau <span class="text-danger">(*)</span> la bat buoc</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="ibox">
                    <div class="ibox-content">
                        <div class="row ">
                            <div class="col-lg-6 mb10">
                                <div class="form-row">
                                    <label class="control-label text-left" for="">Email</label>
                                    <span class="text-danger">(*)</span>
                                    <input type="text" name="email" value="{{ old('email', $user->email ?? '') }}"
                                        class="form-control" placeholder="" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-lg-6 mb10">
                                <div class="form-row">
                                    <label class="control-label text-left" for="">Họ tên</label>
                                    <span class="text-danger">(*)</span>
                                    <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}"
                                        class="form-control" placeholder="" autocomplete="off">
                                </div>
                            </div>
                            @php
                                $userCatalogue = ['[Chọn nhóm thành viên]', 'Cộng tác viên', 'Quản trị viên'];
                            @endphp
                            <div class="col-lg-6 mb10">
                                <div class="form-row">
                                    <label class="control-label text-left " for="">Nhóm thành viên</label>
                                    <div class="user_option_form ">
                                        <select class="form-control setupSelect2" name="user_catalogue_id"
                                            value="{{ old('user_catalogue_id') }}" id="">[Chon nhom
                                            thanh vien]
                                            @foreach ($userCatalogue as $key => $item)
                                                {
                                                <option
                                                    {{ $key == old('user_catalogue_id', isset($user->user_catalogue_id) ? $user->user_catalogue_id : '') ? 'selected' : '' }}
                                                    value="{{ $key }}">{{ $item }}</option>
                                                }
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 mb10">
                                <div class="form-row">
                                    <label class="control-label text-left" for="">Ngày sinh</label>
                                    <input type="date" id="" name="birthday"
                                        value="{{ old('birthday', isset($user->birthday) ? \Carbon\Carbon::parse($user->birthday)->format('Y-m-d') : '') }}"
                                        class="form-control" placeholder="dd/mm/yyyy" autocomplete="off">
                                </div>
                            </div>
                            @if ($config['method'] == 'create')
                                <div class="col-lg-6 mb10">
                                    <div class="form-row">
                                        <label class="control-label text-left" for="">Mật khẩu</label>
                                        <span class="text-danger">(*)</span>
                                        <input type="password" name="password"
                                            value="{{ old('password', $username->password ?? '') }}"
                                            class="form-control" placeholder="" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-lg-6 mb10">
                                    <div class="form-row">
                                        <label class="control-label text-left" for="">Nhập lại mật khẩu</label>
                                        <span class="text-danger">(*)</span>
                                        <input type="password" name="reenter_password"
                                            value="{{ old('password', $username->password ?? '') }}"
                                            class="form-control" placeholder="" autocomplete="off">
                                    </div>
                                </div>
                            @endif

                            <div class="col-lg-12 mb10">
                                <div class="form-row">Ảnh đại diện
                                    <label class="control-label text-left" for=""></label>
                                    <input type="text" name="image" value="{{ old('image', $user->image ?? '') }}"
                                        class="form-control upload-image" placeholder="" autocomplete="off"
                                        data-upload="Images">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-lg-5">
                <div class="panel-head">
                    <div class="panel-title">
                        Thông tin liên hệ
                    </div>
                    <div class="panel-description">
                        Nhập thông tin liên hệ của người dùng
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="ibox">
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-lg-6 mb10">
                                <label class="control-label text-left" for="">Chọn thành phố</label>
                                <div class="user_option_form ">
                                    <select class="setupSelect2 form-control provinces location " name="province_id"
                                        data-target="districts" id="">[Chọn
                                        thành
                                        phố]
                                        <option value="0">[Chọn thành phố]</option>
                                        @if (isset($provinces))


                                            @foreach ($provinces as $province)
                                                <option @if (old('province_id') == $province->code) selected @endif
                                                    value="{{ $province->code }}">{{ $province->name }}</option>
                                            @endforeach
                                        @endif

                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6 mb10">
                                <label class="control-label text-left" for="">Chọn quận/huyện</label>
                                <select class="form-control districts setupSelect2 location" name="district_id"
                                    data-target="wards" id="">[Chọn
                                    quận/huyện]
                                    <option value="0">[Chọn quận/huyện]</option>
                                </select>
                            </div>
                            <div class="col-lg-6 mb10">
                                <label class="control-label text-left" for="">Chọn phường/xã</label>
                                <select class="form-control wards setupSelect2 location" name="ward_id">[Chọn
                                    phường/xã]
                                    <option value="0">[Chọn phường/xã]</option>
                                </select>
                            </div>
                            <div class="col-lg-6 mb10">
                                <div class="form-row">
                                    <label class="control-label text-left" for="">Địa chỉ</label>
                                    <input type="text" name="address"
                                        value="{{ old('$user->address', $user->address ?? '') }}"
                                        class="form-control" placeholder="" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-lg-6 mb10">
                                <div class="form-row">
                                    <label class="control-label text-left" for="">Số điện thoại</label>
                                    <input type="text" name="phone"
                                        value="{{ old('$user->phone', $user->phone ?? '') }}" class="form-control"
                                        placeholder="" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-lg-6 mb10">
                                <div class="form-row">
                                    <label class="control-label text-left" for="">Ghi chú</label>
                                    <input type="text" name="description"
                                        value="{{ old('$user->description', $user->description ?? '') }}"
                                        class="form-control" placeholder="" autocomplete="off">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-right mb10">
            <button class="btn btn-w-m btn-primary" type="submit" name="send" value="send">
                Lưu lại
            </button>
        </div>
    </div>
</form>

<script>
    var province_id = '{{ isset($user->province_id) ? $user->province_id : old('province_id') }}'
    var district_id = '{{ isset($user->district_id) ? $user->district_id : old('district_id') }}'
    var ward_id = '{{ isset($user->ward_id) ? $user->ward_id : old('ward_id') }}'
    // $(document).ready(function() {
    //     $('.setupSelect2').select2({
    //         placeholder: "[Chọn nhóm thành viên]", // Optional: placeholder text
    //         // allowClear: true // Optional: allow clearing the selection
    //     });
    // });
</script>
