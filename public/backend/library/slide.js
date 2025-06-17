(function ($) {
    "use strict";
    var count = 0;
    var doc = $(document);

    // Function để lấy old values từ server-side (Laravel)
    var getOldSlideData = () => {
        console.log('window.oldSlideData', window.oldSlideData);
        // Giả sử bạn truyền old values từ Laravel vào JavaScript
        return window.oldSlideData || [];
    };

    // Function để lấy edit data từ server-side
    var getEditSlideData = () => {
        // Giả sử bạn truyền edit data từ Laravel vào JavaScript
        console.log('window.editSlideData', window.editSlideData);
        return window.editSlideData || [];
    };

    var addSlide = (type) => {

        doc.on('click', '.addSlide', function (e) {
            e.preventDefault();
            if (typeof (type) == 'undefined') {
                type = 'Images'
            }
            var finder = new CKFinder();
            finder.resourceType = type;
            finder.selectActionFunction = function (fileUrl, data, allFiles) {
                for (let i = 0; i < allFiles.length; i++) {
                    let image = allFiles[i].url;
                    $('.sortable-list-slide').append(renderSlideItemHtml(image));
                }
                checkSlideAlert();
            }
            finder.popup();
        });
    }

    var renderSlideItemHtml = ($image, slideData = {}) => {
        // Sử dụng index được truyền từ bên ngoài hoặc timestamp để đảm bảo unique
        let slideId = slideData.id || (++count);
        let tabId1 = 'tab-' + slideId + '-1';
        let tabId2 = 'tab-' + slideId + '-2';

        // Lấy dữ liệu từ slideData hoặc để trống
        let description = slideData.description || '';
        let url = slideData.url || '';
        let openNewTab = slideData.open_new_tab || '';
        let title = slideData.title || '';
        let alt = slideData.alt || '';
        let isChecked = openNewTab === '_blank' ? 'checked' : '';

        let html = `<li class="col-lg-12 ui-state-default">
                <div class="slide-item">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="thumb col-lg-3">
                                <span class="span image img-scaledown">
                                    <img src="${$image}" alt="">
                                    <input type="hidden" name="slide[image][]" value="${$image}">
                                </span>
                                <button class="delete-image" type="button">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                            <div class="col-lg-9">
                                <div class="tabs-container">
                                    <ul class="nav nav-tabs">
                                        <li class="active"><a data-toggle="tab" href="#${tabId1}"
                                                aria-expanded="false">Thông tin chung</a></li>
                                        <li class=""><a data-toggle="tab" href="#${tabId2}"
                                                aria-expanded="true">SEO</a></li>
                                    </ul>
                                    <div class="tab-content">
                                        <div id="${tabId1}" class="tab-pane active">
                                            <div class="panel-body">
                                                <div class="left-slide-wrapper-tab1">
                                                    <div class="label-text mb8">Mô tả</div>
                                                    <div class="form-row mb10">
                                                        <textarea class="form-control" 
                                                            name="slide[description][]" 
                                                            placeholder="Mô tả ngắn về slide">${description}</textarea>
                                                    </div>
                                                    <div class="url-new-tap-wrapper uk-flex uk-flex-middle uk-flex-between mb10">
                                                        <div class="slide-input-border col-lg-9">
                                                            <input type="text" 
                                                                name="slide[url][]"
                                                                value="${url}"
                                                                class="form-control col-lg-8 mb10"
                                                                placeholder="Nhập đường dẫn URL">
                                                        </div>
                                                        <div class="slide-open-new-tab1-wrapper col-lg-3 uk-flex uk-flex-middle">
                                                            <label class="control-label col-lg-11 text-right mr10"
                                                                for="open_new_tab-checkbox-${slideId}">Mở trong tab mới</label>
                                                            <input type="checkbox"
                                                                id="open_new_tab-checkbox-${slideId}"
                                                                name="slide[open_new_tab][]" 
                                                                value="_blank"
                                                                ${isChecked}
                                                                class="form-control text-left open_new_tab-checkbox">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="${tabId2}" class="tab-pane">
                                            <div class="panel-body">
                                                <div class="left-slide-wrapper-tab1">
                                                    <div class="url-new-tap-wrapper mb10">
                                                        <div class="slide-input-border col-lg-12">
                                                            <label for="slide-title-${slideId}">Tiêu đề ảnh</label>
                                                            <input type="text" 
                                                                name="slide[title][]"
                                                                id="slide-title-${slideId}"
                                                                value="${title}"
                                                                class="form-control col-lg-8 mb10"
                                                                placeholder="Nhập tiêu đề ảnh">
                                                        </div>
                                                        <div class="slide-input-border col-lg-12">
                                                            <label for="slide-alt-${slideId}">Mô tả ảnh</label>
                                                            <input type="text" 
                                                                name="slide[alt][]"
                                                                id="slide-alt-${slideId}"
                                                                value="${alt}"
                                                                class="form-control col-lg-8 mb10"
                                                                placeholder="Nhập mô tả ảnh">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
            </li>`;
        return html;
    }



    var checkSlideAlert = () => {
        let slideCount = $('.slide-item').length;
        if (slideCount === 0) {
            $('.slide-empty-message').show();
        } else {
            $('.slide-empty-message').hide();
        }
    }
    var deletePicture = () => {
        doc.on('click', '.delete-image', function (e) {
            let _this = $(this);
            _this.parents('.ui-state-default').remove();
            if ($('.ui-state-default').length < 1) {
                $('.click-to-upload').removeClass('hidden');
                $('.upload-list').addClass('hidden');
            }
            e.preventDefault();
            checkSlideAlert();
        });
    }

    // Function để load old values hoặc edit data khi trang load
    var loadExistingSlides = () => {
        let oldData = getOldSlideData();
        let editData = getEditSlideData();
        console.log('oldData', oldData);
        console.log('editData', editData);

        // Ưu tiên old data (khi validation fail)
        let dataToLoad = oldData.length > 0 ? oldData : editData;

        if (dataToLoad.length > 0) {
            $('.slide-empty-message').hide();
            dataToLoad.forEach((slideData, index) => {
                let slideHtml = renderSlideItemHtml(slideData.image, slideData);
                $('.sortable-list-slide').append(slideHtml);
            });
        }

        checkSlideAlert();
    }

    // Document ready
    $(document).ready(function () {
        console.log('Document ready');
        console.log('window.editSlideData:', window.editSlideData);
        console.log('window.oldSlideData:', window.oldSlideData);

        addSlide();
        deletePicture();
        loadExistingSlides(); // Load existing data
    });

})(jQuery);