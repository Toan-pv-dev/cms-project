<div class="ibox">
    <div class="box-title">
        <div class="uk-flex uk-flex-middle uk-flex-between">
            <h5>Album ảnh</h5>
            <div class="upload-album"><a href="" class="upload-picture mr10">Chọn hình ảnh</a></div>
        </div>
    </div>
    <div class="ibox-content">
        <div class="row">
            <div class="col-lg-12">
                @if (!isset($album) || count($album) == 0)
                    <div class="click-to-upload ">
                        <div class="icon">
                            <a href="" class="upload-picture">
                                <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80"
                                    viewBox="0 0 24 24">
                                    <path
                                        d="M9 12c0-.552.448-1 1.001-1s.999.448.999 1-.446 1-.999 1-1.001-.448-1.001-1zm6.2 0l-1.7 2.6-1.3-1.6-3.2 4h10l-3.8-5zm5.8-7v-2h-21v15h2v-13h19zm3 2v14h-20v-14h20zm-2 2h-16v10h16v-10z"
                                        fill="#ced9d2" />
                                </svg>
                            </a>
                        </div>


                        <div class="samll-text">
                            Sử dụng nút chọn hình hoặc click vào đây để thêm hình ảnh
                        </div>
                    </div>
                @endif
                {{-- {{ $album }} --}}
                @if (isset($album) && count($album))
                    <div class="upload-list {{ count($album) ? '' : 'hidden' }}">
                        <ul name="album" id="sortable" class="clearfix data-album sortui ui-sortable">
                            @foreach ($album as $key => $val)
                                <li class="ui-state-default">
                                    <div class="thumb">
                                        <span class="span image img-scaledown">
                                            <img src=" {{ $val }} " alt=" {{ $val }} ">
                                            <input type="hidden" name="album[]" value=" {{ $val }} ">
                                        </span>
                                        <button class="delete-image">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
