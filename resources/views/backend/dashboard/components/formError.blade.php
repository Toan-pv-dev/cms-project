@if ($errors->any())
    <div class="alert alert-danger" role="alert">

        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </div>

@endif
