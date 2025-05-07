<form action="{{ route('attribute.index') }}">
    <div class="filter-wrapper">
        <!-- On larger screens, each element gets more space -->
        <div class="component-filter d-flex ">
            @php
                $perpage = request('perpage') ?: old('perpage');
                $publishArray = [0 => 'UnPublished', 1 => 'published'];
                $publish = request()->has('publish') ? request('publish') : old('publish', -1);
                $attributeCatalogueId = request('attribute_catalogue_id') ?: old('attribute_catalogue_id');

            @endphp

            <div class="left-component-filter col-lg-6 col-md-6 mb-2" style="padding: 0">
                <div class="perpage-select col-lg-5 p0">
                    <select name="perpage" class="form-control w-100">
                        @for ($i = 20; $i <= 200; $i += 20)
                            <option {{ $perpage == $i ? 'selected' : '' }} value="{{ $i }}">
                                {{ $i }}
                                ban ghi</option>
                        @endfor
                    </select>
                </div>


            </div>
            <div class="right-component-filter col-lg-6 col-md-3 mb-2 mr-10 p0">
                <div class=" perpage-select col-lg-3 col-md-4">
                    <select name="attribute_catalogue_id" class="form-control setupSelect2 publish">
                        @foreach ($dropdown as $key => $val)
                            <option {{ $attributeCatalogueId == $key ? 'selected' : '' }} value="{{ $key }}">
                                {{ $val }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class=" perpage-select col-lg-3">
                    <select name="publish" class="form-control setup-select2 publish">
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


                <div class=" search-input-form  col-lg-4 col-md-6 mb-2 mr5">
                    <input type="text" name="keyword" class="form-control w-100" placeholder="Enter your keys"
                        value="{{ request('keyword') }}">
                    <button type="submit" class="btn btn-info w-100 h-100" style="background-color: #1ab394;">Tìm
                        kiếm</button>
                </div>
                <div class=" filter-add-component  ">
                    <a href="{{ route('attribute.create') }}"><button type="button" class="btn btn-warning w-100">
                            <i style="margin-right: 6px"
                                class="glyphicon glyphicon-plus"></i>{{ config('apps.attribute.create_index') }}</button></a>
                </div>
            </div>
        </div>
    </div>
</form>
