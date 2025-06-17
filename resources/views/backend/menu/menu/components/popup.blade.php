<div id="modal-form" class="modal fade">
    <form action="{{ route('menu.store') }}" method="post" class="form-horizontal create-menu-catalogue">
        @csrf
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">Thêm vị trí hiển thị</h4>
                </div>

                <div class="modal-body">
                    <div class="form-error"></div>
                    <div class="row">
                        <div class="col-lg-12 mb10">
                            <div class="item-input">
                                <label class="control-label text-left" for="name">Tên vị trí hiển thị</label>
                                <span class="text-danger">(*)</span>
                                <input type="text" name="name" value="{{ old('name') }}"
                                    class="form-control mt10" placeholder="" autocomplete="off">
                                <div class="error name"></div>
                            </div>
                            <div class="item-input">
                                <label class="control-label text-left" for="keyword">Tên vị trí hiển thị</label>
                                <span class="text-danger">(*)</span>
                                <input type="text" name="keyword" value="{{ old('keyword') }}"
                                    class="form-control mt10" placeholder="" autocomplete="off">
                                <div class="error keyword"></div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-white">
                        Close
                    </button>
                    <button type="submit" name="create" value="create" class="btn btn-primary">Lưu lại
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
