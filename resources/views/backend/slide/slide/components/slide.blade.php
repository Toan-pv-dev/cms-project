{{-- Xử lý dữ liệu PHP trước --}}

{{-- Xử lý dữ liệu old và edit --}}
@php
    // Xử lý old data
    $oldSlideData = [];
    if (old('slide.image')) {
        foreach (old('slide.image') as $index => $image) {
            $oldSlideData[] = [
                'id' => 'old_' . $index . '_' . time(),
                'image' => $image,
                'description' => old('slide.description.' . $index, ''),
                'url' => old('slide.url.' . $index, ''),
                'open_new_tab' => old('slide.open_new_tab.' . $index, ''),
                'title' => old('slide.title.' . $index, ''),
                'alt' => old('slide.alt.' . $index, ''),
            ];
        }
    }

    // Xử lý edit data

    if (isset($slide['item']) && is_array($slide['item'])) {
        $slideIndex = 0;
        foreach ($slide['item'] as $groupKey => $slideGroup) {
            if (is_array($slideGroup)) {
                foreach ($slideGroup as $slideItem) {
                    $editSlideData[] = [
                        'id' => 'edit_' . $slideIndex,
                        'image' => $slideItem['name'] ?? '',
                        'description' => $slideItem['description'] ?? '',
                        'url' => $slideItem['canonical'] ?? '',
                        'open_new_tab' => $slideItem['open_new_tab'] ?? '',
                        'title' => $slideItem['title'] ?? '',
                        'alt' => $slideItem['alt'] ?? '',
                    ];
                    $slideIndex++;
                }
            }
        }
        // dd($editSlideData);
    }
@endphp
<div class="col-lg-9">
    <div class="ibox">
        <div class="panel-head">
            <div class="ibox-title">
                <div class="uk-flex uk-flex-middle uk-flex-between">
                    <h5>Danh sách slides</h5>
                    <a style="margin-bottom: 6px" href="javascript:void(0)" class="btn btn-primary btn-sm addSlide">
                        <i class="fa fa-plus"></i> Thêm Slide
                    </a>
                </div>

                <div class="ibox-content">
                    <div class="row slide-list">
                        <ul id="sortable" class="clearfix data-album sortui ui-sortable sortable-list-slide">
                            {{-- Danh sách slide sẽ được tạo bằng JavaScript --}}
                        </ul>

                        <div class="text-danger slide-empty-message" style="display: none;">
                            Chưa có hình ảnh nào được chọn
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Script truyền dữ liệu --}}
<script>
    window.editSlideData = {!! json_encode($editSlideData ?? [], JSON_UNESCAPED_UNICODE) !!};
    window.oldSlideData = {!! json_encode($oldSlideData ?? [], JSON_UNESCAPED_UNICODE) !!};
</script>



{{-- Debug info (có thể xóa trong production) --}}
{{-- @if (config('app.debug'))
    <div class="col-lg-12">
        <pre style="background: #f5f5f5; padding: 10px; margin-top: 20px;">
Debug Old Data: {!! json_encode($oldSlideData, JSON_PRETTY_PRINT) !!}

Debug Edit Data: {!! json_encode($editSlideData, JSON_PRETTY_PRINT) !!}
    </pre>
    </div>
@endif --}}
