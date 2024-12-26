<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>
                <input type="checkbox" value="" id="checkAll" class="input-checkbox">
            </th>
            <th>Ảnh</th>
            <th>Tên nhóm</th>
            {{-- <th>Canonical</th> --}}
            <th>Tình trạng</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @if (isset($posts) && is_object($posts))
            @foreach ($posts as $post)
                <tr>
                    <td>
                        <input type="checkbox" value="{{ $post->id }}" class="input-checkbox checkBoxItem">
                    </td>
                    <td style="width: 100px; height: 60px; text-align: center;">
                        <span><img style=" width: 100%;height: 100%;object-fit: cover; border-radius: 5px; "
                                src="{{ $post->image }}" alt=""></span>
                    </td>

                    <td>
                        {{ $post->name }}
                    </td>
                    {{-- <td>
                        {{ $post->image }}
                    </td> --}}
                    <td class="text-center js-switch-{{ $post->id }}">


                        {{-- {{ $user->id }} --}}
                        <input type="checkbox" data-field="publish" data-model="post" class="js-switch status"
                            {{ $post->publish == 1 ? 'checked' : '' }} value="{{ $post->publish }}"
                            data-modelId="{{ $post->id ?? '' }}" />

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
