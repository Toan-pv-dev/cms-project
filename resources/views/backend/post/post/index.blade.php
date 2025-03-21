<div class="row wrapper border-bottom gray-bg page-heading">
    @include('backend.dashboard.components.breadcrumb', ['title' => $config['seo']['index']['title']])
    <div class="col-lg-12 mg12">
        <div class="ibox float-e-margins">

            {{-- <h5>{{ config('apps.user.tiltle') }}</h5> --}}
            @include('backend.dashboard.components.toolbox', ['model' => 'PostCatalogue'])

            <div class="ibox-content">
                @include('backend.post.post.components.filter')
                @include('backend.post.post.components.table')
            </div>
        </div>
    </div>
</div>
