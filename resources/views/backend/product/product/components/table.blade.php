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
        {{-- @dd($products); --}}
        @if (isset($products) && is_object($products))
            @foreach ($products as $product)
                <tr id="{{ $product->id }}">
                    <td>
                        <input type="checkbox" value="{{ $product->id }}" class="input-checkbox checkBoxItem">
                    </td>

                    <td>
                        <div class="uk-flex uk-flex-middle">
                            <div class="image mr5 image-post">
                                <img src="{{ $product->image }}" alt="{{ $product->name }}" class="img-cover">
                            </div>
                            <div class="main-info">
                                <div class="main_title">
                                    <span>{{ $product->name }}</span>
                                </div>
                                <div class="catalogues">
                                    <span class="text-danger">Nhóm hiển thị</span>
                                    @foreach ($product->product_catalogues as $val)
                                        @foreach ($val->product_catalogue_language as $cat)
                                            <a href="">{{ $cat->name }}</a>
                                        @endforeach
                                    @endforeach

                                </div>

                            </div>

                        </div>
                    </td>
                    <td>
                        <input type="number" name="order" class="form-control sort-order text-center"
                            data-id="{{ $product->id }}" data-model="{{ $config['model'] }}"
                            value="{{ $product->order }}">
                    </td>

                    <td class="text-center js-switch-{{ $product->id }}">
                        <input type="checkbox" data-field="publish" data-model="{{ $config['model'] }}"
                            class="js-switch status" {{ $product->publish == 1 ? 'checked' : '' }}
                            value="{{ $product->publish }}" data-modelId="{{ $product->id ?? '' }}" />

                    </td>
                    <td class="edit-btn-group" style="text-align:center; position: relative">
                        <div class="" style="position: relative, display: inline-block">
                            <a class="btn btn-success" href="{{ route('product.edit', $product->id) }}"><i
                                    class="fa fa-edit"></i></a>
                            <a class="btn btn-warning" href="{{ route('product.delete', $product->id) }}"><i
                                    class="fa fa-trash-o"></i></a>
                        </div>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
{{ $products->links('pagination::bootstrap-4') }}
