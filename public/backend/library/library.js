(function ($) {
    // "use strict";
    var _token = $('meta[name="csrf-token"]').attr('content');
    // Switchery initialization
    var switcheryInit = () => {
        $('.js-switch').each(function () {
            new Switchery(this, { color: '#1AB394' });
        });
    };

    // Select2 initialization
    // var select2Init = () => {
    //     $('.setupSelect2').select2();
    // };

    var changeStatus = () => {
        $(document).on('change', '.status', function (e) {
            e.preventDefault();
            console.log("Status changed");
            let _this = $(this)
            let option = {
                'value': _this.val(),
                'modelId': _this.attr('data-modelId'),
                'model': _this.attr('data-model'),
                'field': _this.attr('data-field'),
                '_token': _token

            }

            $.ajax({
                url: '/ajax/dashboard/changeStatus', // The server endpoint where the data will be sent
                type: 'POST', // Method to send the request (e.g., POST or GET)
                data: option,
                dataType: 'json',
                success: function (response) {
                    let inputValue = ((option.value == 1) ? 2 : 1)
                    if (response.flag == true) {
                        _this.val(inputValue)
                    }
                    console.log(response);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    // Handle any errors
                    console.error("AJAX error:" + textStatus + errorThrown);
                }
            });

        })
    }

    var toggleRowBackground = () => {
        // Sự kiện cho checkbox "checkAll" để chọn hoặc bỏ chọn tất cả
        $(document).on('click', '#checkAll', function () {
            let checkAllState = $(this).prop('checked');
            $('.checkBoxItem').prop('checked', checkAllState);

            $('.checkBoxItem').each(function () {
                let _this = $(this);
                if (checkAllState) {
                    _this.closest('tr').addClass('active-bg');
                } else {
                    _this.closest('tr').removeClass('active-bg');
                }
            });
        });

        // Sự kiện cho từng checkbox "checkBoxItem" để thay đổi nền của từng dòng
        $(document).on('click', '.checkBoxItem', function () {
            let _this = $(this);
            if (_this.prop('checked')) {
                _this.closest('tr').addClass('active-bg');
            } else {
                _this.closest('tr').removeClass('active-bg');
            }
            let allChecked = $('.checkBoxItem').length === $('.checkBoxItem:checked').length;
            $('#checkAll').prop('checked', allChecked);
        });
    }

    var sortui = () => {
        $("#sortable").sortable();
        $("sortable").disableSelection();
    }
    var changeStatusAll = () => {
        if ($('.changeStatusAllOn, .changeStatusAllOff').length) {
            $(document).on('click', '.changeStatusAllOn, .changeStatusAllOff', function (e) {
                e.preventDefault();

                let _this = $(this);
                let id = [];
                $('.checkBoxItem').each(function () {
                    let checkBox = $(this);
                    if (checkBox.prop('checked')) {
                        id.push(checkBox.val());
                    }
                });

                let option = {
                    'value': _this.attr('data-value'),
                    'model': _this.attr('data-model'),
                    'field': _this.attr('data-field'),
                    'id': id,
                    '_token': _token
                };

                $.ajax({
                    url: '/ajax/dashboard/changeStatusAll',
                    type: 'POST',
                    data: option,
                    dataType: 'json',
                    success: function (response) {
                        // console.log(response);
                        // Kiểm tra nếu request thành công
                        if (response.flag) { // Giả sử server trả về response.success = true nếu thành công
                            let cssActive1 = 'background-color: rgb(26, 179, 148); border-color: rgb(26, 179, 148); box-shadow: rgb(26, 179, 148) 0px 0px 0px 16px inset; transition: border 0.4s, box-shadow 0.4s, background-color 1.2s;'
                            let cssActive2 = 'left: 20px; transition: background-color 0.4s, left 0.2s;'
                            let cssInActive1 = 'background-color: rgb(255, 255, 255); border-color: rgb(223, 223, 223); box-shadow: rgb(223, 223, 223) 0px 0px 0px 0px inset; transition: border 0.4s, box-shadow 0.4s;'
                            let cssInActive2 = 'left: 0px; transition: background-color 0.4s, left 0.2s;'
                            if (option.value == 1) {
                                for (let i = 0; i < id.length; i++) {
                                    let switchContainer = $('.js-switch-' + id[i]).find('span.switchery');
                                    switchContainer.attr('style', cssActive1); // Apply cssActive1 to the containe
                                    switchContainer.children('small').attr('style', cssActive2);
                                }
                            }
                            else {
                                for (let i = 0; i < id.length; i++) {
                                    let switchContainer = $('.js-switch-' + id[i]).find('span.switchery');
                                    switchContainer.attr('style', cssInActive1); // Apply cssActive1 to the container
                                    // Apply cssActive2 to the `small` element inside the Switchery container
                                    switchContainer.children('small').attr('style', cssInActive2);
                                }
                            }

                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.error("AJAX error:" + textStatus + errorThrown);
                    }
                });
            });
        }
    }



    // $(document).ready(function () {
    //     changeStatusAll(); // Gọi hàm khi tài liệu đã sẵn sàng
    // });

    // Document ready
    $(document).ready(function () {
        switcheryInit(); // Initialize Switchery
        // select2Init();
        changeStatus();
        toggleRowBackground();
        changeStatusAll();
        sortui();
        // Initialize Select2;
    });

})(jQuery);
