{{-- @include('backend.dashboard.components.breadcrumb', [
    'title' =>
        $config['method'] == 'create' ? $config['seo']['create']['title'] : $config['seo']['update']['title'],
]) --}}
@if ($errors->any())
    <div class="alert alert-danger" role="alert">

        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </div>

@endif
@php
    switch ($config['method']) {
        case 'create':
            $url = route('menu.store');
            break;
        case 'update':
            $url = route('menu.update', $menu->id);
            break;
        case 'editMenu':
            $url = route('menu.store', $id);
            break;
        default:
            $url = route('menu.store');
    }
@endphp
<form action="{{ $url }}" class="box" method="post">
    @csrf
    <div class="wrapper wrapper-content animated fadeInRight">
        @include('backend.menu.menu.components.catalogue')
        <hr>
        @include('backend.menu.menu.components.list')

        <div class="text-right mb10">
            <button class="btn btn-w-m btn-primary" type="submit" name="send" value="send">
                Lưu lại
            </button>
        </div>
    </div>
</form>
@include('backend.menu.menu.components.popup')
