<div class="row">
    <div class="col-lg-5">
        <div class="panel-head">
            <div class="ibox-content">
                <div class="panel-body">
                    <div class="panel-group" id="accordion">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <div class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">Tạo mới
                                        menu</a>
                                </div>
                            </div>
                            <div id="collapseOne" class="panel-collapse collapse in">
                                <div class="panel-body">
                                    <div class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">Tạo
                                            Menu</a>
                                    </div>
                                    <div class="panel-description">
                                        <p>+ Cài đặt Menu mà bạn muốn hiển thị.</p>
                                        <p><small class="text-danger">+ Khi khởi tạo menu bạn phải chắc chắn rằng
                                                đường
                                                dẫn của menu có hoạt động. Đường dẫn trên website được khởi tạo tại
                                                các
                                                module: Bài viết, Sản phẩm, Dự án,...</small></p>
                                        <p><small class="text-danger">+ Tiêu đề và đường dẫn của menu không được bỏ
                                                trống</small></p>
                                        <p><small class="text-danger">+ Hệ thống chỉ hỗ trợ tối đa 5 cấp
                                                menu</small>
                                        </p>

                                    </div>
                                    <a style="font-style:none !important" href=""
                                        class="btn btn-w-m btn-default add-menu">Nhập
                                        đường dẫn</a>

                                </div>
                            </div>
                        </div>
                        @foreach (__('module.model') as $key => $val)
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a class="collapsed menu-module" data-toggle="collapse" data-parent="#accordion"
                                            data-model="{{ $key }}"
                                            href="#{{ $key }}">{{ $val }}</a>
                                    </h4>
                                </div>
                                <div id="{{ $key }}" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <form action="" method="get" data-model="{{ $key }}"
                                            class="search-model">
                                            <input value="" name="keyword" type="text"
                                                class="form-control search-menu" placeholder="Nhập 2 ký tự để tìm kiếm">
                                        </form>
                                    </div>
                                    <div class="menu-list">

                                    </div>
                                </div>
                            </div>
                        @endforeach


                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="ibox">
            <div class="ibox-content">
                <div class="row">
                    <div class="col-lg-4">
                        <label for="">Tên Menu</label>
                    </div>
                    <div class="col-lg-4">
                        <label for="">Đường dẫn</label>
                    </div>
                    <div class="col-lg-4">
                        <label for="">Vị trí</label>
                    </div>
                </div>
                <div class="hr-line-dashed" style="margin:10p 0;"></div>

                <div class="menu-wrapper">
                    @php
                        $menuData =
                            $menus ??
                            old('menu', [
                                'name' => [],
                                'canonical' => [],
                                'order' => [],
                                'id' => [],
                            ]);
                        $hasData = false;
                        $length = 0;

                        // Kiểm tra có dữ liệu không và tính toán length an toàn
                        if (is_array($menuData) && isset($menuData['name']) && is_array($menuData['name'])) {
                            $length = count($menuData['name']);
                            $hasData = $length > 0;
                        }
                    @endphp

                    <div class="notice notification text-center" @if ($hasData) hidden @endif>
                        <h4 style="font-weight: 600">Danh sách liên kết này chưa có bất kỳ đường dẫn nào.</h4>
                        <p>Hãy nhấn vào <span style="color: blue">"Thêm đường dẫn"</span> để bắt đầu thêm</p>
                    </div>

                    @if ($hasData)
                        @for ($index = 0; $index < $length; $index++)
                            <div class="row menu-item mb10" data-canonical="{{ $menuData['canonical'][$index] ?? '' }}">
                                <div class="col-lg-4">
                                    <input type="text" name="menu[name][]"
                                        value="{{ $menuData['name'][$index] ?? '' }}" class="form-control"
                                        placeholder="Tên menu..." />
                                </div>

                                <div class="col-lg-4">
                                    <input type="text" name="menu[canonical][]"
                                        value="{{ $menuData['canonical'][$index] ?? '' }}" class="form-control"
                                        placeholder="Đường dẫn..." />
                                </div>

                                <div class="col-lg-2">
                                    <input type="number" name="menu[order][]"
                                        value="{{ $menuData['order'][$index] ?? 0 }}" class="form-control"
                                        placeholder="0" />
                                </div>

                                <div class="col-lg-2">
                                    <div class="form-row text-center">
                                        <a href="javascript:void(0);" class="delete-menu text-danger">
                                            <i class="fa fa-trash-o"></i>
                                        </a>
                                    </div>
                                </div>

                                <input type="hidden" name="menu[id][]" value="{{ $menuData['id'][$index] ?? 0 }}">
                            </div>
                        @endfor
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>

{{-- Debug section - có thể xóa khi không cần thiết --}}
<pre>
    {{ print_r(old('menu'), true) }}
</pre>
