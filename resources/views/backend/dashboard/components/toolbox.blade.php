<div class="ibox-title">
    <h5>Danh sách thành viên </h5>
    <div class="ibox-tools">
        <a class="collapse-link">
            <i class="fa fa-chevron-up"></i>
        </a>
        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
            <i class="fa fa-wrench"></i>
        </a>
        <ul class="dropdown-menu dropdown-user">
            <li><a href="#" class="changeStatusAllOn" data-field="publish" data-model="{{ $model }}"
                    data-value="1">Pulish
                    toàn bộ thành viên</a>
            </li>
            <li><a href="#" class="changeStatusAllOff" data-field="publish" data-model="{{ $model }}"
                    data-value="0">Unpublish toàn bộ thành viên</a>

            </li>
        </ul>
        <a class="close-link">
            <i class="fa fa-times"></i>
        </a>
    </div>
</div>
