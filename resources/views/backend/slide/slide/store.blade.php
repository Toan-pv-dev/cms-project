@include('backend.dashboard.components.breadcrumb', [
    'title' =>
        $config['method'] == 'create' ? $config['seo']['create']['title'] : $config['seo']['update']['title'],
])
@if ($errors->any())
    <div class="alert alert-danger" role="alert">

        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </div>

@endif
@php
    $url = $config['method'] == 'create' ? route('slide.store') : route('slide.update', $slide->id);
@endphp
<form action="{{ $url }}" class="box" method="post">
    @csrf
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            @include('backend.slide.slide.components.slide')
            @include('backend.slide.slide.components.aside', [
                'slide' => $slide ?? null,
                'config' => $config,
            ])
        </div>
    </div>
</form>
