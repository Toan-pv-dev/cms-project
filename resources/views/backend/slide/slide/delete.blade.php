@include('backend.dashboard.components.breadcrumb', ['title' => $config['seo']['create']['title']])
@if ($errors->any())
    <div class="alert alert-danger" role="alert">

        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </div>

@endif

<form action="{{ route('user.destroy', $user->id) }}" class="box" method="post">
    @csrf
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-5">
                <div class="panel-head">
                    <div class="panel-title">
                        Thong tin chung
                    </div>
                    <div class="panel-description">
                        <p>- Luu y: Bạn không thể khôi phục thông tin của người dùng sau khi xóa <span
                                class="text-danger">(*)</span> la bat buoc</p>
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
                                        class="form-control" placeholder="" autocomplete="off" readonly>
                                </div>
                            </div>
                            <div class="col-lg-6 mb10">
                                <div class="form-row">
                                    <label class="control-label text-left" for="">Ho ten</label>
                                    <span class="text-danger">(*)</span>
                                    <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}"
                                        class="form-control" placeholder="" autocomplete="off" readonly>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr>

        <div class="text-right mb10">
            <button class="btn btn-w-m btn-danger" type="submit" name="send" value="send">
                Xóa dữ liệu
            </button>
        </div>
    </div>
</form>
