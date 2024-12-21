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
            HT.SetupCkFinder2(object, 'Images')
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
    HT.SetupCkFinder2 = (object, type) => {
        if (typeof (type) == 'undefined') {
            type = 'Images'
        }
        var finder = new CKFinder();
        finder.resourceType = type;
        finder.selectActionFunction = function (fileUrl, data) {
            object.val(fileUrl);
        }
        finder.popup();
    }


    // Document ready
    $(document).ready(function () {
        HT.uploadImageToInput();
        HT.setupCkEditor();
        HT.uploadImageAvatar();
        HT.multipleUploadCkeditor();
    });

})(jQuery);
