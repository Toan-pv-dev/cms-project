(function ($) {
    "use strict";

    var niceSelect = () => {
        $('.nice-select').niceSelect();
    };

    var showWrapper = () => {
        $(document).on('change', '.variant-checkbox', function (e) {
            let productCode = $('input[name="product_code"]').val();
            let price = $('input[name="product_price"]').val();

            if (price === '' || productCode === '') {
                alert('Vui lòng nhập mã sản phẩm và giá sản phẩm');

                // Ngăn không cho checkbox thay đổi trạng thái
                e.preventDefault();
                return;
            }
            else {
                $('.variant-wrapper').toggleClass('hidden');
            }
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

        $('.variant-item select.choose-attribute').each(function () {
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

    // click button để thêm variant 
    var addVariant = () => {
        if ($('.add-variant').length) {
            $(document).on('click', '.add-variant', function () {
                $('.variantTable thead').html('');
                $('.variantTable tbody').html('');

                var totalAttributes = attributeCatalogue.length;
                var currentCount = $('.variant-item').length;

                if (currentCount >= totalAttributes) {
                    alert('Bạn chỉ có thể chọn tối đa ' + totalAttributes + ' nhóm thuộc tính.');
                    return;
                }

                var newVariant = $('.variant-item').first().clone();
                // newVariant.find('input').val('');

                // Reset phần select2 nếu tồn tại
                newVariant.find('.selectVariant').parent().html('<input type="text" class="fake-variant form-control" disabled>');

                // Reset select choose-attribute
                var selectElement = newVariant.find('select.choose-attribute');
                selectElement.next('.nice-select').remove();
                selectElement.show();

                // Lấy các ID đã chọn ở các dòng khác
                var selectedIds = [];
                $('.variant-item select.choose-attribute').each(function () {
                    var val = $(this).val();
                    if (val && val !== '') selectedIds.push(val);
                });
                var newIndex = $('.variant-item').length;
                selectElement.attr('name', 'attribute_catalogue_id[' + newIndex + ']');


                // Bắt đầu dựng lại option
                selectElement.html('<option value="">Chọn nhóm thuộc tính</option>');
                attributeCatalogue.forEach(function (item) {
                    var disabledAttr = selectedIds.includes(String(item.id)) ? 'disabled' : '';
                    selectElement.append('<option value="' + item.id + '" ' + disabledAttr + '>' + item.name + '</option>');
                });

                newVariant.addClass('mt10');
                $('.variant-item').last().after(newVariant);
                selectElement.niceSelect();
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
            if ($('.variant-item').length === 1) {
                alert('Cần ít nhất một phiên bản.');
                return;
            }

            $(this).closest('.variant-item').remove();

            $('.variant-item').each(function (index) {
                $(this).find('select.choose-attribute').attr('name', 'attribute_catalogue_id[' + index + ']');

                // Cập nhật lại hidden input
                $(this).find('input[type=hidden]').attr('name', 'attributes[' + index + '][catalogue_id]');

                // Cập nhật lại select2
                let select2 = $(this).find('select.selectVariant');
                if (select2.length) {
                    select2
                        .attr('name', 'attributes[' + index + '][values][]')
                        .attr('data-index', index)
                }
            });

            updateAllSelectOptions();
            createVariant();
        });
    };

    var removeVariantForm = () => {
        $(document).on('click', '.cancleUpdate ', function () {
            $(this).closest('.updateVariantRow').remove();
        })
    }



    var select2Variant = (attributeCatalogueId, index) => {
        let html = '<select class="selectVariant form-control variant-' + attributeCatalogueId +
            '" name="attributes[' + index + '][values][]" multiple data-catid="' + attributeCatalogueId +
            '" data-index="' + index + '"></select>';
        return html
    }





    var chooseVariantGroup = () => {
        $(document).on('change', '.choose-attribute', function () {
            let _this = $(this)
            let attributeCatalogueId = _this.val()
            let variantIndex = _this.closest('.variant-item').index();
            if (attributeCatalogueId != 0) {
                let hiddenInput = '<input type="hidden" name="attributes[' + variantIndex + '][catalogue_id]" value="' + attributeCatalogueId + '">';
                _this.parents('.col-md-3').siblings('.col-md-8').html(
                    hiddenInput + select2Variant(attributeCatalogueId, variantIndex)
                );
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
        console.log('option:', option);
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

    var createProductVariant = () => {
        $(document).on('change', '.selectVariant', function () {
            let _this = $(this)
            createVariant()
        })
    }

    var createVariant = () => {
        let attributes = [];

        $('.variant-item').each(function () {
            let _this = $(this);
            let attr = [];
            let attributeCatalogueId = _this.find('.choose-attribute').val();
            console.log('attributeCatalogueId:', attributeCatalogueId);
            let optionText = _this.find('.choose-attribute option:selected').text();
            let attribute = $('.variant-' + attributeCatalogueId).select2('data');

            for (let i = 0; i < attribute.length; i++) {
                let item = {};
                item[optionText] = attribute[i].text;
                attr.push(item);
                item[optionText + '_id'] = attribute[i].id;
            }
            attributes.push(attr);
        });

        // attributes vẫn dùng để render bảng như cũ
        attributes = attributes.reduce(
            (a, b) => a.flatMap(d => b.map(e => ({ ...d, ...e })))
        );


        // Truy xuất dễ dàng

        createTableHeader(attributes);
    }
    var createTableHeader = (attributes) => {
        if (!attributes.length) {
            $('.variantTable thead').html('');
            return;
        }
        const attributeKeys = Object.keys(attributes[0]).filter(k => !k.endsWith('_id'));
        let theadHtml = '<tr>';
        theadHtml += '<th>Ảnh</th>';
        let variantName = attributeKeys.forEach(key => {
            theadHtml += `<th>${key}</th>`;
        });
        theadHtml += `<th>Tên file</th>`;
        theadHtml += `<th>ID</th>`;
        theadHtml += `<th>Số lượng</th>`;
        theadHtml += `<th>Giá tiền</th>`;
        theadHtml += `<th>SKU</th>`;
        theadHtml += '</tr>';
        $('.variantTable thead').html(theadHtml);
        createVariantRow(attributes);
    };

    var createVariantRow = (attributes) => {
        if (attributes.length === 0) {
            $('.variantTable tbody').html('');
            return;
        }
        const attributeKeys = Object.keys(attributes[0]).filter(k => !k.endsWith('_id'));

        let selectedIds = [];
        $('.variant-item').each(function () {
            let _this = $(this);
            let attributeCatalogueId = _this.find('.choose-attribute').val();
            let attribute = $('.variant-' + attributeCatalogueId).select2('data');
            for (let i = 0; i < attribute.length; i++) {
                selectedIds.push(String(attribute[i].id));
            }
        });

        // 2. Xóa các dòng variant-row không còn thuộc tính nào trong selectedIds
        $('.variant-row').each(function () {
            let $row = $(this);
            // Lấy tất cả id của dòng này (từ cột ID, phân tách bởi dấu phẩy)
            let ids = $row.find('td').eq(attributeKeys.length + 1).text().split(',').map(s => s.trim());
            console.log('ids:', ids); // ['1', '2', ...]
            let shouldRemove = ids.some(id => !selectedIds.includes(id));
            if (shouldRemove) $row.remove();
        });

        // 3. Append dòng mới nếu chưa có
        function getVariantKey(attribute, attributeKeys) {
            return attributeKeys.map(k => `${k}-${attribute[k]}`).join('_').replace(/\s+/g, '-').toLowerCase();
        }
        attributes.forEach((attribute, index) => {
            let price = $('#product_price').val();
            let sku = $('#product_code').val();
            let currentIndex = $('.variant-row').length;

            let quantity = (typeof oldVariants !== 'undefined' && oldVariants[currentIndex] && oldVariants[currentIndex]['quantity']) ? oldVariants[currentIndex]['quantity'] : '';
            let key = getVariantKey(attribute, attributeKeys);
            if (!$(`.variant-row[data-key="${key}"]`).length) {
                let ids = attributeKeys.map(k => attribute[k + '_id']).filter(Boolean).join('-');
                let tbodyHtml = `
            <tr class="variant-row" data-key="${key}" data-index="${$('.variant-row').length}">
                <td>
                    <div class="image-post">
                        <img class="img-cover" src="https://via.placeholder.com/80" alt="Image">
                    </div>
                </td>
            `;
                attributeKeys.forEach(k => {
                    tbodyHtml += `<td>${attribute[k]}</td>`;
                });
                let variantName = attributeKeys.map(k => attribute[k]).join(',');
                tbodyHtml += `<td><input type="hidden" name="variants[${currentIndex}][name]" class="form-control variant_name" placeholder="Tên phiên bản" value="${variantName}"></td>`;

                tbodyHtml += `<td>${ids}</td>`;
                tbodyHtml += `
                    <td><input type="text" name="variants[${currentIndex}][quantity]" class="form-control variant_quantity" placeholder="Số lượng" value="${quantity}"></td>
                    <td><input type="text" name="variants[${currentIndex}][price]" class="form-control variant_price" placeholder="Giá" value="${price}"></td>
                    <td><input type="text" name="variants[${currentIndex}][sku]" class="form-control variant_sku" placeholder="SKU" value="${sku}-${ids}"></td>
                    <td><input type="hidden" name="variants[${currentIndex}][album]" class="form-control variant_album" placeholder="Album"></td>
                    <td><input type="hidden" name="variants[${currentIndex}][file_name]" class="form-control variant_file_name" placeholder="Tên file"></td>
                    <td><input type="hidden" name="variants[${currentIndex}][file_path]" class="form-control variant_file_url" placeholder="Đường dẫn file"></td>
                    <td><input type="hidden" name="variants[${currentIndex}][attribute_ids]" class="form-control" value="${ids}"></td>
                </tr>`;
                $('.variantTable tbody').append(tbodyHtml);
            }
        });
    };



    var variantAlbum = () => {
        $(document).on('click', '.click-to-upload-variant', function (e) {
            e.preventDefault()
            browseVariantServerAlbum();
        }
        )
    }
    var deleteVariantPicture = () => {
        $(document).on('click', '.delete-image', function (e) {
            e.preventDefault()

            let _this = $(this)
            _this.parents('.ui-state-default').remove()
            if ($('.ui-state-default').length < 1) {
                $('.click-to-upload-variant').removeClass('hidden')
                $('.upload-list-variant').addClass('hidden')

            }
        })

    }

    var browseVariantServerAlbum = (e) => {

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
                html += '<input type="hidden" name="variantAlbum[]" value="' + image + '">'
                html += '</span>'
                html += '<button class="delete-image">'
                html += '<i class="fa fa-trash"></i>'
                html += '</button></div></li>'


            }
            $('.click-to-upload-variant').addClass('hidden')
            $('#sortable2').append(html)
            $('.upload-list-variant').removeClass('hidden')
            console.log(123);
        }
        finder.popup();

    }

    var switchChange = () => {
        $(document).on('change', '.js-switch', function () {
            let _this = $(this)
            let isChecked = _this.prop('checked')
            let quantityValue = _this.closest('.row').find('input[name="quantity"]').val()
            let targetWrapper = _this.closest('.row').find('.col-lg-10')

            if (isChecked || quantityValue != '') {
                console.log('isChecked:', isChecked);
                targetWrapper.find('input:disabled').removeAttr('disabled')

            } else {
                targetWrapper.find('.isDisabled').attr('disabled', true)

            }

        })
    }


    var updateVariant = () => {
        $(document).on('click', '.variant-row', function () {
            // Xóa mọi form update khác
            $('.updateVariantRow').remove();

            let rowIndex = $(this).data('index');
            let html = $($('#variant-attribute-template').html());
            html.attr('data-index', rowIndex);
            $(this).after(html);

            let targetRow = $(this);
            html.find('input[name="td-quantity"]').val(targetRow.find('input.variant_quantity').val());
            html.find('input[name="td-sku"]').val(targetRow.find('input.variant_sku').val());
            html.find('input[name="td-price"]').val(targetRow.find('input.variant_price').val());
            html.find('input[name="td-barcode"]').val(targetRow.find('input.variant_barcode').val());
            html.find('input[name="variant-file-name"]').val(targetRow.find('input.variant_file_name').val());
            html.find('input[name="variant-path"]').val(targetRow.find('input.variant_file_url').val());

            // --- Xử lý album preview ---
            let albumVal = targetRow.find('input.variant_album').val();
            console.log('albumVal:', albumVal); // 'image1.jpg, image2.jpg, ...'
            let albumArr = albumVal ? albumVal.split(',') : [];
            let albumHtml = '';
            albumArr.forEach(function (imgUrl) {
                imgUrl = imgUrl.trim();
                if (imgUrl) {
                    albumHtml += `
                    <li class="ui-state-default">
                        <div class="thumb">
                            <span class="span image img-scaledown">
                                <img src="${imgUrl}" alt="${imgUrl}">
                                <input type="hidden" name="variantAlbum[]" value="${imgUrl}">
                            </span>
                            <button class="delete-image">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    </li>
                `;
                }
            });
            if (albumArr.length < 0) {
                html.find('input[name="variantAlbum[]"]').each(function () {
                    albumArr.push($(this).val());
                });
            }
            console.log('albumArr:', albumArr); // ['image1.jpg', 'image2.jpg', ...]



            html.find('#sortable2').html(albumHtml);
            // Ẩn/hiện nút chọn hình
            if (albumArr.length > 0) {
                html.find('.click-to-upload-variant').addClass('hidden');
                html.find('.upload-list-variant').removeClass('hidden');
            } else {
                html.find('.click-to-upload-variant').removeClass('hidden');
                html.find('.upload-list-variant').addClass('hidden');
            }

            switcheryInit();
        });
    };

    // Hàm khởi tạo Switchery
    var switcheryInit = () => {
        $('.js-switch').each(function () {
            if (!$(this).data('initialized')) {
                new Switchery(this, { color: '#1AB394' });
                $(this).data('initialized', true);
            }
        });
    };


    var saveVariantUpdate = () => {
        $(document).on('click', '.saveUpdate', function () {
            let parentRow = $(this).closest('.updateVariantRow');
            let rowIndex = parentRow.data('index'); // Lấy index của dòng hiện tại
            let variantAlbum = parentRow.find('input[name="variantAlbum[]"]').map(function () {
                return $(this).val();
            }).get();
            let variantValue = {
                'variant_quantity': parentRow.find('input[name="td-quantity"]').val(),
                'variant_sku': parentRow.find('input[name="td-sku"]').val(),
                'variant_price': parentRow.find('input[id="variantPrice"]').val(),
                'variant_barcode': parentRow.find('input[id="VariantBarcode"]').val(),
                'variant_file_name': parentRow.find('input[name="variant-file-name"]').val(),
                'variant_file_url': parentRow.find('input[name="variant-path"]').val(),
                'variant_album': variantAlbum,
            };

            console.log(rowIndex);

            // Tìm dòng tương ứng trong bảng hiển thị và cập nhật giá trị
            let targetRow = $(`.variantTable tbody tr[data-index="${rowIndex}"]`);
            $.each(variantValue, function (key, value) {
                targetRow.find(`.${key}`).val(value);
            });
            let firstImg = variantAlbum.length > 0 ? variantAlbum[0] : 'https://via.placeholder.com/80';
            // Lấy ảnh đầu tiên
            targetRow.find('.img-cover').attr('src', firstImg);
            let price = $('.variantPrice').val();
            console.log(price);


            parentRow.remove();
        });
    };

    var setupSelectMultiple = () => {
        $('.selectVariant').each(function () {
            let _this = $(this);
            let catalogueId = _this.data('catid');

            // Tìm object attribute tương ứng
            let item = attribute.find(attr => parseInt(attr.catalogue_id) === parseInt(catalogueId));
            console.log('item:', item);
            if (item && item.values) {
                $.get('ajax/attribute/loadAttribute', {
                    catalogue_id: item.catalogue_id,
                    values: item.values
                }, function (jsonData) {
                    if (jsonData && jsonData.items) {
                        _this.empty();
                        for (let i = 0; i < jsonData.items.length; i++) {
                            let option = new Option(jsonData.items[i].text, jsonData.items[i].id, true, true);
                            _this.append(option).trigger('change');
                        }
                    }
                });
            }

            getSelect2(_this); // Gọi select2 sau khi load xong
        });
    };



    $(document).ready(function () {
        niceSelect();
        showWrapper();
        addVariant();
        onChangeSelect();
        removeVariant();
        chooseVariantGroup();
        createProductVariant();
        variantAlbum();
        deleteVariantPicture();
        switchChange();
        updateVariant();
        removeVariantForm();
        saveVariantUpdate();
        setupSelectMultiple();

    });
    ;
})(jQuery);
