<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>
                <input type="checkbox" value="" id="checkAll" class="input-checkbox">
            </th>
            <th>Tiêu đề</th>
            <th style="width: 80px" class="text-center">Vị trí</th>
            <th class="text-center">Tình trạng</th>

            <th class="text-center">Action</th>
        </tr>
    </thead>
    <tbody>
        @if (isset($posts) && is_object($posts))
            @foreach ($posts as $post)
                <tr id="{{ $post->id }}">
                    <td>
                        <input type="checkbox" value="{{ $post->id }}" class="input-checkbox checkBoxItem">
                    </td>

                    <td>
                        <div class="uk-flex uk-flex-middle">
                            <div class="image mr5 image-post">
                                <img src="{{ $post->image }}" alt="{{ $post->name }}" class="img-cover">
                            </div>
                            <div class="main-info">
                                <div class="main_title">
                                    <span>{{ $post->name }}</span>
                                </div>
                                <div class="catalogues">
                                    <span class="text-danger">Nhóm hiển thị</span>
                                    @foreach ($post->post_catalogues as $val)
                                        @foreach ($val->post_catalogue_language as $cat)
                                            <a href="">{{ $cat->name }}</a>
                                        @endforeach
                                    @endforeach

                                </div>

                            </div>

                        </div>
                    </td>
                    <td>
                        <input type="number" name="order" class="form-control sort-order text-center"
                            data-id="{{ $post->id }}" data-model="{{ $config['model'] }}"
                            value="{{ $post->order }}">
                    </td>

                    <td class="text-center js-switch-{{ $post->id }}">
                        <input type="checkbox" data-field="publish" data-model="{{ $config['model'] }}"
                            class="js-switch status" {{ $post->publish == 1 ? 'checked' : '' }}
                            value="{{ $post->publish }}" data-modelId="{{ $post->id ?? '' }}" />

                    </td>
                    <td class="edit-btn-group" style="text-align:center; position: relative">
                        <div class="" style="position: relative, display: inline-block">
                            <a class="btn btn-success" href="{{ route('post.edit', $post->id) }}"><i
                                    class="fa fa-edit"></i></a>
                            <a class="btn btn-warning" href="{{ route('post.delete', $post->id) }}"><i
                                    class="fa fa-trash-o"></i></a>
                        </div>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
{{ $posts->links('pagination::bootstrap-4') }}
