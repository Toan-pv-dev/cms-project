(function ($) {
    "use strict";
    var HT = {};
    HT.setupCkEditor = () => {
        if ($('.ck-editor')) {
            $('.ck-editor').each(function () {
                let elementId = $(this).attr('id')
                let dataHeight = $(this).attr('data-height')
                HT.ckeditor4(elementId, dataHeight)
            })
        }
    }

    HT.uploadAlbum = () => {
        $(document).on('click', '.upload-picture', function (e) {
            e.preventDefault()
            HT.browseServerAlbum();
        })
    }

    HT.ckeditor4 = (elementId, dataHeight) => {
        CKEDITOR.replace(elementId, {
            height: dataHeight,
            entities: true,
            allowedContent: true,
            toolbar: [
                { name: 'document', items: ['Source', '-', 'Save', 'NewPage', 'Preview', 'Print', '-', 'Templates'] },
                { name: 'clipboard', items: ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'] },
                { name: 'editing', items: ['Find', 'Replace', '-', 'SelectAll'] },
                { name: 'insert', items: ['Image', 'Table', 'HorizontalRule', 'SpecialChar'] },
                '/',
                { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike', '-', 'RemoveFormat'] },
                { name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote'] },
                { name: 'links', items: ['Link', 'Unlink'] },
                { name: 'styles', items: ['Styles', 'Format'] }
            ],
            // Chỉ định ngôn ngữ (mặc định là 'en')
            language: 'vi'
        });

        // Tùy chỉnh ngôn ngữ (localization)
        CKEDITOR.plugins.setLang('sourcearea', 'vi', {
            toolbar: 'Mã HTML' // Đổi tên nút Source thành "Mã HTML"
        });
    };
    // Switchery initialization
    HT.uploadImageToInput = () => {
        $('.upload-image').click(function () {
            let input = $(this)
            let type = input.attr('data-type')
            HT.SetupCkFinder2(input, type);
        })
    }

    HT.uploadImageAvatar = () => {
        $('.img-target').click(function () {
            let input = $(this)
            let type = 'Images'
            HT.browseServerAvatar(input, type);
        })
    }

    HT.multipleUploadCkeditor = () => {
        $(document).on('click', '.multipleUploadImageCkeditor', function (e) {
            let object = $(this)
            let target = object.attr('data-target')
            HT.SetupCkFinder2(object, 'Images', target)
            e.preventDefault()

        })
    }


    HT.browseServerAvatar = (object, type) => {
        if (typeof (type) == 'undefined') {
            type = 'Images'
        }
        var finder = new CKFinder();
        finder.resourceType = type;
        finder.selectActionFunction = function (fileUrl, data) {
            object.attr('src', fileUrl);
            object.siblings('input').val(fileUrl);
        }
        finder.popup();
    }
    HT.SetupCkFinder2 = (object, type, target) => {
        if (typeof (type) == 'undefined') {
            type = 'Images'
        }
        var finder = new CKFinder();
        let html = '';
        finder.resourceType = type;
        finder.selectActionFunction = function (fileUrl, data, allFiles) {
            let html = '';
            for (let i = 0; i < allFiles.length; i++) {
                var image = allFiles[i].url;
                html += '<div style="margin-bottom: 15px; ">' +
                    '<figure>' +
                    '<img src="' + image + '" alt="image" style="width:100%">' +
                    '<figcaption>Nhập mô tả cho ảnh</figcaption>' +
                    '</figure>' +
                    '</div>';

            }
            html += '<p style="clear: both;"></p>';
            CKEDITOR.instances[target].insertHtml(html)
        }
        finder.popup();
    }

    HT.browseServerAlbum = () => {
        var type = 'Images';
        var finder = new CKFinder();
        finder.resourceType = type;
        finder.selectActionFunction = function (fileUrl, data, allFiles) {
            var html = '';
            for (let i = 0; i < allFiles.length; i++) {
                var image = allFiles[i].url;
                html += '<li class="ui-state-default">'
                html += '<div class="thumb">'
                html += '<span class="span image img-scaledown">'
                html += '<img src="' + image + '" alt="' + image + '">'
                html += '<input type="hidden" name="album[]" value="' + image + '">'
                html += '</span>'
                html += '<button class="delete-image">'
                html += '<i class="fa fa-trash"></i>'
                html += '</button></div></li>'


            }

            $('.click-to-upload').addClass('hidden')
            $('#sortable').append(html)
            $('.upload-list').removeClass('hidden')
        }
        finder.popup();
    }
    HT.deletePicture = () => {
        $(document).on('click', '.delete-image', function (e) {
            let _this = $(this)
            _this.parents('.ui-state-default').remove()
            if ($('.ui-state-default').length == 0) {
                $('.click-to-upload').removeClass('hidden')
                $('.upload-list').addClass('hidden')

            }
            e.preventDefault()
        })

    }

    // Document ready
    $(document).ready(function () {
        HT.uploadImageToInput();
        HT.setupCkEditor();
        HT.uploadImageAvatar();
        HT.multipleUploadCkeditor();
        HT.uploadAlbum();
        HT.deletePicture();
    });

})(jQuery);
