@include('backend.dashboard.components.breadcrumb', [
    'title' => $config['method'] == 'show' ? $config['seo']['show']['title'] : $config['seo']['create']['title'],
])
@if ($errors->any())
    <div class="alert alert-danger" role="alert">

        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </div>

@endif

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-4">
            <div class="panel-title">Danh sách menu</div>
            <div class="panel-body">
                <p> + Danh sách Menu giúp bạn dễ dàng kiểm soát bố cục menu. Bạn có thể thêm mới hoặc cập nhật menu bằng
                    nút <span class="text-success">Cập nhật Menu</span></p>
                <p>Bạn có thể thay đổi vị trí hiển thị của menu bằng cách ấn vào nút <span class="text-success"> menu con
                        đến vị
                        trí mong muốn</span>
                </p>
                <p class="text-danger">Hỗ trợ danh mục con 5 cấp</p>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="ibox">
                <div class="ibox-title">
                    <div class="ibox-title uk-flex uk-flex-middle uk-flex-between">
                        <h5 style="margin:0">Menu chính</h5>
                        <a href="{{ route('menu.editMenu', $id) }}" class="custome-button">Cập nhật Menu
                    </div>
                </div>
                @php
                    $menus = recursive($menus);
                @endphp
                <div class="ibox-content" data-catalogue-id="{{ $id }}">
                    @if (count($menus))
                        <div class="dd" id="nestable2">
                            {!! recursive_menu($menus) !!}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
