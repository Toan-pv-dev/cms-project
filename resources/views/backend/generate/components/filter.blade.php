<form action="{{ route('generate.index') }}">
    <div class="filter-wrapper">
        <!-- On larger screens, each element gets more space -->
        <div class="component-filter d-flex ">
            @php
                $perpage = request('perpage') ?: old('perpage');

            @endphp
            <div class="left-component-filter col-lg-2 col-md-6 mb-2" style="padding: 0">
                <select name="perpage" class="form-control setupSelect2">
                    @for ($i = 20; $i <= 200; $i += 20)
                        <option {{ $perpage == $i ? 'selected' : '' }} value="{{ $i }}">
                            {{ $i }}
                            ban ghi</option>
                    @endfor
                </select>
            </div>
            @php
                $publishArray = [0 => 'UnPublished', 1 => 'published'];
                $publish = request()->has('publish') ? request('publish') : old('publish', -1);
            @endphp
            <div class="perpage-select col-lg-3">
                <select name="publish" class="form-control publish setupSelect2">
                    @if (is_array(config('apps.general.publish')))
                        @foreach (config('apps.general.publish') as $key => $val)
                            <option {{ isset($publish) && $publish == $key ? 'selected' : '' }}
                                value="{{ $key }}">
                                {{ $val }}
                            </option>
                        @endforeach
                    @else
                        <option value="-1">No options available</option>
                    @endif

                </select>
            </div>

            <div class="search-input-form col-lg-4 col-md-6 mb-2 mr5">
                <input type="text" name="keyword" class="form-control w-100" placeholder="Enter your keys"
                    value="{{ request('keyword') }}">
                <button type="submit" class="btn btn-info w-100" style="background-color: #1ab394;">Tìm
                    kiếm</button>
            </div>
            <div class="filter-add-component ">
                <a href="{{ route('generate.create') }}"><button type="button" class="btn btn-warning w-100">
                        <i style="margin-right: 6px" class="glyphicon glyphicon-plus"></i>Thêm module moi</button></a>
            </div>
        </div>
    </div>
    </div>
</form>
