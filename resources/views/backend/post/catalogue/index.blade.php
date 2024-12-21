<div class="row wrapper border-bottom gray-bg page-heading">
    @include('backend.dashboard.components.breadcrumb', ['title' => $config['seo']['index']['title']])
    <div class="col-lg-12 mg12">
        <div class="ibox float-e-margins">

            {{-- <h5>{{ config('apps.user.tiltle') }}</h5> --}}
            @include('backend.dashboard.components.toolbox', ['model' => 'PostCatalogue'])

            <div class="ibox-content">
                @include('backend.post.catalogue.components.filter')
                @include('backend.post.catalogue.components.table')
            </div>
        </div>
    </div>
</div>

{{-- <script>
    $(document).ready(function() {
        var elem = document.querySelector('.js-switch');
        var switchery = new Switchery(elem, {
            color: '#1AB394'
        });
    })
</script> --}}
