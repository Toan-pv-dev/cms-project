<base href="{{ config('app.url') }}">
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>INSPINIA | Dashboard v.2</title>

@if (isset($config['css']) && is_array($config['css']))
    @foreach ($config['css'] as $val)
        {!! '<link href="' . asset($val) . '" rel="stylesheet">' !!}
    @endforeach
@endif
{{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/uikit@3.21.16/dist/css/uikit.min.css" /> --}}
{{-- <link href="{{ asset('backend/css/uikit.min.css') }}" rel="stylesheet"> --}}

<link href="{{ asset('backend/css/bootstrap.min.css') }}" rel="stylesheet">

<link href="{{ asset('backend/font-awesome/css/font-awesome.css') }}" rel="stylesheet">
<link href="{{ asset('backend/css/animate.css') }}" rel="stylesheet">
<link href="{{ asset('backend/css/style.css') }}" rel="stylesheet">
<link href="{{ asset('backend/css/custome.css') }}" rel="stylesheet">
<!-- Select2 CSS -->
{{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" /> --}}

<!-- Select2 JS -->
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script> --}}

<script src="{{ asset('backend/js/jquery-3.1.1.min.js') }}"></script>
<script src="{{ asset('backend/js/plugins/flot/jquery.flot.js') }}"></script>
<script src="{{ asset('backend/js/inspinia.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/uikit@3.21.16/dist/js/uikit.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/uikit@3.21.16/dist/js/uikit-icons.min.js"></script>
<script>
    var BASE_URL = '{{ config('app.url') }}'
    var SUFFIX = '{{ config('apps.general.suffix') }}'
</script>
