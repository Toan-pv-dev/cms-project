(function ($) {
    "use strict";
    var HT = {};
    var _token = $('meta[name="csrf-token"]').attr('content');
    var doc = $(document);


    var searchModel = () => {

        const debounce = (func, delay) => {
            let timeout;
            return function () {
                const context = this;
                const args = arguments;
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    func.apply(context, args);
                }, delay);
            };
        };
        const handleSearch = function () {
            let _this = $(this)
            let keyword = _this.val()
            if ($('input[type=radio]:checked').length == 0) {
                alert('Bạn chưa chọn Module');
                return
            }

            let option = {
                model: $('input[type=radio]:checked').val(),
                keyword: keyword,
            }
            if ((option.keyword.length) >= 2) {
                sendAjaxGetResult(option.model, option.keyword);
            }
            else
                $('.ajax-search-result').html('').addClass('hidden');
        }
        doc.on('keyup', '.search-model', debounce(handleSearch, 300));
    }
    var sendAjaxGetResult = (model, keyword) => {
        $.ajax({
            url: 'ajax/dashboard/getWidgetSearchResults',
            type: 'GET',
            data: {
                model: model,
                keyword: keyword
            },
            dataType: 'json',
            success: function (res) {
                let container = $('.ajax-search-result');
                container.html(''); // clear old results
                console.log(res.length)
                if (res.length) {
                    res.forEach(item => {
                        let html = `
                        <div class="ajax-search-result-item mb5">
                            <button class="ajax-search-item full-width uk-flex uk-flex-middle uk-flex-between">
                                <span class="item-text">${item.pivot_name}</span>
                                <span class="item-icon"><i class="fa fa-check text-success"></i></span>
                                <input class="widget-image-input" type="hidden" value = "${item.image}"
                            </button>
                        </div>`;
                        container.append(html);
                    });
                    container.removeClass('hidden');
                } else {
                    container.html('<p class="text-muted">Không tìm thấy kết quả phù hợp.</p>').removeClass('hidden');
                }
            },
            error: function (err) {
                console.error('Lỗi:', err);
                $('.ajax-search-result').html('<div class="text-danger">Đã có lỗi xảy ra</div>').removeClass('hidden');
            }
        });
    };
    const hideSearchResultWhenClickOutside = () => {
        $(document).on('click', function (event) {
            if (!$(event.target).closest('.search-model, .ajax-search-result').length) {
                $('.ajax-search-result').addClass('hidden').html('');
            }
        });
    };
    // Hàm xử lý khi click vào item
    const handleClickSearchResultItem = () => {
        $(document).on('click', '.ajax-search-item', function (e) {
            e.preventDefault()
            const item = $(this).closest('.ajax-search-result-item');
            const name = item.find('.item-text').text().trim();

            let thumb = item.find('.widget-image-input').val() || 'https://i.ytimg.com/vi/vH8kYVahdrU/maxresdefault.jpg'; // fallback ảnh

            // Nếu đã được thêm rồi thì không thêm nữa
            if ($(`.result-item-container .name:contains("${name}")`).length > 0) {
                return;
            }

            const html = `
            <div class="result-item uk-flex uk-flex-middle uk-flex-between">
                <div class="uk-flex uk-flex-middle item-content">
                    <span class="width-thumb">
                        <img src="${thumb}" alt="thumbnail">
                    </span>
                    <span class="name">${name}</span>
                </div>
                <span class="remove-item" style="cursor:pointer;">
                    <i class="fa fa-times"></i>
                </span>
            </div>
        `;
            $('.result-item-container').append(html);
            $('.ajax-search-result').addClass('hidden').html('');
            $('.search-model').val('');
        });
    };

    const handleRemoveItem = () => {
        $(document).on('click', '.remove-item', function () {
            $(this).closest('.result-item').remove();
        });
    };



    // Document ready
    $(document).ready(function () {
        searchModel();
        hideSearchResultWhenClickOutside();
        handleClickSearchResultItem();
        handleRemoveItem();
    });

})(jQuery);
