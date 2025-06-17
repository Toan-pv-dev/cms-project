<div class="row wrapper border-bottom gray-bg page-heading">
    @include('backend.dashboard.components.breadcrumb', ['title' => $config['seo']['index']['title']])
    <div class="col-lg-12 mg12">
        <div class="ibox float-e-margins">

            {{-- <h5>{{ config('apps.slide.tiltle') }}</h5> --}}
            @include('backend.dashboard.components.toolbox', ['model' => 'Slide'])

            <div class="ibox-content">
                @include('backend.slide.slide.components.filter')
                @include('backend.slide.slide.components.table')
            </div>
        </div>
    </div>
</div>
