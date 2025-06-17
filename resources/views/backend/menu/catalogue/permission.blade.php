@include('backend.dashboard.components.breadcrumb', [
    'title' => $config['seo']['permission']['title'],
])


<form action="{{ route('user.catalogue.updatePermission') }}" class="box" method="post">
    @csrf

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Cấp quyền</h5>
                    </div>
                </div>
                <div class="ibox-content">
                    <table class="table table-striped table-bordered">
                        <tr>
                            <th></th>
                            @foreach ($userCatalogues as $userCatalogue)
                                <th class="text-center">{{ $userCatalogue->name }}</th>
                            @endforeach
                        </tr>
                        @foreach ($permissions as $permission)
                            <tr>
                                <td><a class="uk-flex uk-flex-middle uk-flex-between">{{ $permission->name }}<span
                                            style="color: red;">({{ $permission->canonical }})</span></a></td>
                                @foreach ($userCatalogues as $userCatalogue)
                                    <td><input
                                            {{ in_array($permission->id, $userCatalogue->permissions->pluck('id')->toArray()) ? 'checked' : '' }}
                                            type="checkbox" name="permission[{{ $userCatalogue->id }}][]"
                                            value="{{ $permission->id }}" class="form-control">
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
        <hr>

        <div class="text-right mb10">
            <button class="btn btn-w-m btn-primary" type="submit" name="send" value="send">
                Lưu lại
            </button>
        </div>
    </div>
</form>
