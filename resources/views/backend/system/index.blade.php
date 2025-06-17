<div class="row wrapper border-bottom gray-bg page-heading">
    @include('backend.dashboard.components.breadcrumb', ['title' => $config['seo']['index']['title']])
</div>
@php $url = isset($config['method']) && $config['method'] == 'translate' ? route('system.save.translate', ['languageId'=> $languageId]) : route('system.store') @endphp
<form action="{{ $url }}" class="box" method="post">
    @csrf
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="language-selector d-flex align-items-center gap-2 mb-3">
                @foreach ($languages as $val)
                    <a href="{{ route('system.translate', ['languageId' => $val->id]) }}"
                        class="language-item {{ request('languageId') == $val->id ? 'active' : '' }}">
                        <div class="language-box">
                            <img src="{{ $val->image }}" alt="Language" class="img-flag">
                        </div>
                    </a>
                @endforeach
            </div>
        </div>

        @foreach ($configData as $configKey => $configVal)
            <div class="row">
                <div class="col-lg-5">



                    <div class="panel-head">
                        <div class="panel-title">
                            {{ $configVal['label'] }}
                        </div>
                        <div class="panel-description">
                            <p>{{ $configVal['description'] }}</span>
                            <p>- Luu y: Nhung truong hop danh dau <span class="text-danger">(*)</span> la bat buoc</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="ibox">
                        @if (count($configVal['value']))
                            <div class="ibox-content">
                                @foreach ($configVal['value'] as $key => $item)
                                    @php
                                        $name = $key . '_' . $configKey;
                                    @endphp
                                    <div class="row ">
                                        <div class="col-lg-12 mb10">
                                            <div class="form-row">
                                                <label class="control-label text-left uk-flex uk-flex-between"
                                                    for="">
                                                    <span>{{ $item['label'] }}</span>
                                                    <span>{!! renderSystemLink($item) !!}</span>
                                                </label>
                                                @switch($item['type'])
                                                    @case('text')
                                                        {!! renderSystemInput($name, $systems) !!}
                                                    @break

                                                    @case('images')
                                                        {!! renderSystemImages($name, $systems) !!}
                                                    @break

                                                    @case('textarea')
                                                        {!! renderSystemTextarea($name, $systems) !!}
                                                    @break

                                                    @case('select')
                                                        {!! renderSystemSelect($item, $name, $systems) !!}
                                                    @break

                                                    @case('ckeditor')
                                                        {!! renderSystemEditor($name, $systems) !!}
                                                    @break

                                                    @default
                                                @endswitch
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach

        <hr>
        <div class="text-right mb10">
            <button class="btn btn-w-m btn-primary" type="submit" name="send" value="send">
                Lưu lại
            </button>
        </div>
    </div>
</form>
