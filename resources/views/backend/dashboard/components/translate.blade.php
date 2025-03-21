<form class="box" method="post">
    @csrf

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-6">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Thông tin chung</h5>
                    </div>
                    <div class="ibox-content">
                        @include('backend.dashboard.components.general_translate')
                    </div>
                </div>
                @include('backend.dashboard.components.seo_translate', ['disabled' => 1])
            </div>

        </div>
        <div class="text-right mb10">
            <button class="btn btn-w-m btn-primary" type="submit" name="send" value="send">
                Lưu lại
            </button>
        </div>


    </div>
</form>
