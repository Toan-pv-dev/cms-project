(function ($) {
    "use strict";
    var HT = {};
    HT.seoPreview = () => {
        $('input[name=meta_title]').on('input', function () {
            // Lấy giá trị từ input
            let inputValue = $(this).val();

            // Cập nhật nội dung heading
            $('.meta-title').text(inputValue);
        });
        $('input[name=canonical]').css({
            'padding-left': parseInt($('.baseUrl').outerWidth() + 10)
        });
        $('input[name=canonical]').on('keyup', function () {
            let inputValue = $(this).val()

            // Chuẩn hóa dữ liệu bằng cách loại bỏ dấu tiếng Việt
            let value = HT.removeUtf8(inputValue);

            $('.canonical').html(BASE_URL + value + SUFFIX);
        });
        $('input[name=translate_canonical]').css({
            'padding-left': parseInt($('.baseUrl').outerWidth() + 10)
        });
        $('input[name=translate_canonical]').on('keyup', function () {
            let inputValue = $(this).val()

            // Chuẩn hóa dữ liệu bằng cách loại bỏ dấu tiếng Việt
            let value = HT.removeUtf8(inputValue);

            $('.canonical').html(BASE_URL + value + SUFFIX);
        });
        $('textarea[name=meta_description]').on('keyup', function () {
            // Lấy giá trị từ input
            let inputValue = $(this).val();

            // Cập nhật nội dung heading
            $('.meta_description').html(inputValue);
        });


    }
    HT.removeUtf8 = (str) => {
        if (!str) return '';
        return str.normalize('NFD').replace(/[\u0300-\u036f]/g, '') // Loại bỏ dấu
            .replace(/đ/g, 'd').replace(/Đ/g, 'D')             // Chuyển đ thành d
            .replace(/[^a-zA-Z0-9\s]/g, '')                    // Loại bỏ ký tự đặc biệt
            .replace(/\s+/g, '-').toLowerCase();               // Thay khoảng trắng bằng dấu gạch ngang và chuyển thành chữ thường
    }



    // Document ready
    $(document).ready(function () {
        HT.seoPreview();
        // HT.updateInputPadding();

        // Cập nhật lại khi nội dung baseUrl thay đổi (nếu cần)
        // $(window).on('resipze', HT.updateInputPadding);
    });

})(jQuery);
