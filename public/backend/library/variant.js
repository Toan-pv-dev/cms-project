(function ($) {
    "use strict";

    var niceSelect = () => {
        $('.nice-select').niceSelect();
    };

    var showWrapper = () => {
        $(document).on('change', '.variant-checkbox', function () {
            $('.variant-wrapper').toggleClass('hidden');
        });
    };

    var updateAllSelectOptions = () => {
        var selectedValues = [];

        // Lấy tất cả các giá trị đang được chọn (trừ rỗng)
        $('.variant-item select').each(function () {
            var val = $(this).val();
            if (val && val !== '') {
                selectedValues.push(val);
            }
        });

        $('.variant-item select').each(function () {
            var currentSelect = $(this);
            var currentValue = currentSelect.val();

            // Lặp lại tất cả option trong select
            currentSelect.find('option').each(function () {
                var optionVal = $(this).val();

                // Reset lại disable trước
                $(this).prop('disabled', false);

                // Nếu option này đang được chọn ở select khác => disable nó
                if (selectedValues.includes(optionVal) && optionVal !== currentValue) {
                    $(this).prop('disabled', true);
                }
            });

            // Rebuild lại niceSelect UI
            currentSelect.niceSelect('update');
        });
    };

    var addVariant = () => {
        if ($('.add-variant').length) {
            $(document).on('click', '.add-variant', function () {
                var totalAttributes = attributeCatalogue.length;
                var currentCount = $('.variant-item').length;

                if (currentCount >= totalAttributes) {
                    alert('Bạn chỉ có thể chọn tối đa ' + totalAttributes + ' nhóm thuộc tính.');
                    return;
                }

                var newVariant = $('.variant-item').first().clone();
                newVariant.find('input').val('');

                var selectElement = newVariant.find('select');
                selectElement.next('.nice-select').remove();
                selectElement.show();

                // Reset lại select mới
                selectElement.html('<option value="">Chọn nhóm thuộc tính</option>');

                // Lấy các ID đã được chọn
                var selectedIds = [];
                $('.variant-item select').each(function () {
                    var val = $(this).val();
                    if (val && val !== '') selectedIds.push(val);
                });

                // Thêm option chưa được chọn
                attributeCatalogue.forEach(function (item) {
                    if (!selectedIds.includes(String(item.id))) {
                        selectElement.append('<option value="' + item.id + '">' + item.name + '</option>');
                    }
                });

                newVariant.addClass('mt10');
                $('.variant-item').last().after(newVariant);
                selectElement.niceSelect();

                updateAllSelectOptions();
            });
        }
    };

    var onChangeSelect = () => {
        // Khi thay đổi chọn thuộc tính
        $(document).on('change', '.variant-item select', function () {
            updateAllSelectOptions();
        });
    };
    var removeVariant = () => {
        $(document).on('click', '.remove-attribute', function () {
            // Nếu chỉ còn 1 dòng thì không xóa
            if ($('.variant-item').length === 1) {
                alert('Cần ít nhất một phiên bản.');
                return;
            }

            $(this).closest('.variant-item').remove();
            updateAllSelectOptions();
        });
    };



    var select2Variant = (attributeCatalogueId) => {
        let html = '<select class = "selectVariant form-control" name = "attribute[' + attributeCatalogueId + '][] "multiple data-catid="' + attributeCatalogueId + '"></select>'
        return html
    }

    var unChooseVariantGroup = () => {
        $(document).on('change', '.choose-attribute', function () {
            let _this = $(this)
            let attributeCatalogueId = _this.val()
            if (attributeCatalogueId == 0) {

                _this.parents('.col-md-3').siblings('.col-md-8').html('<input type="text" name="" disable="" class="fake-variant form-control">')

            }
        })
    }

    var chooseVariantGroup = () => {
        $(document).on('change', '.choose-attribute', function () {
            let _this = $(this)
            let attributeCatalogueId = _this.val()
            if (attributeCatalogueId != 0) {
                // console.log('hi')
                // return false
                _this.parents('.col-md-3').siblings('.col-md-8').html(select2Variant(attributeCatalogueId))
                $('.selectVariant').each(function (key, index) {
                    getSelect2($(this))
                })
            }
            else {
                _this.parents('.col-md-3').siblings('.col-md-8').html('<input type="text" disabled name=""  class="fake-variant form-control ">')
            }

        })
    }



    var getSelect2 = (object) => {
        let option = {
            'attributeCatalogueId': object.attr('data-catid')
        }

        $(object).select2({
            minimumInputLength: 2,
            placeholder: 'Nhap toi thieu 2 ky tu de tim kiem',
            ajax: {
                url: 'ajax/attribute/getAttribute',
                type: 'Get',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        search: params.term,
                        option: option,
                    }
                },
                processResults: function (data) {
                    console.log(data)
                    return {
                        results: $.map(data, function (obj, i) {
                            return obj
                        })
                    }
                },
                cache: true
            }
        });
    }

    $(document).ready(function () {
        niceSelect();
        showWrapper();
        addVariant();
        onChangeSelect();
        removeVariant();
        chooseVariantGroup();

    });
})(jQuery);
