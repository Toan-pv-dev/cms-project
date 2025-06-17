<div class="row wrapper border-bottom gray-bg page-heading">
    @include('backend.dashboard.components.breadcrumb', ['title' => $config['seo']['index']['title']])
    <div class="col-lg-12 mg12">
        <div class="ibox float-e-margins">

            {{-- <h5>{{ config('apps.menu.tiltle') }}</h5> --}}
            @include('backend.dashboard.components.toolbox', ['model' => 'menu'])

            <div class="ibox-content">
                @include('backend.menu.menu.components.filter')
                @include('backend.menu.menu.components.table')
            </div>
        </div>
    </div>
</div>
