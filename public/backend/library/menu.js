(function ($) {

    // Select initialization
    var _token = $('meta[name="csrf-token"]').attr('content');
    var currentKeyword = '';
    "use strict"; // Correct typo here
    // var HT = {};
    var doc = $(document);
    var createMenuCatalogue = () => {
        doc.on('submit', '.create-menu-catalogue', function (e) {
            e.preventDefault()
            let _form = $(this)
            let option = {
                'name': _form.find('input[name=name]').val(),
                'keyword': _form.find('input[name=keyword]').val(),
                '_token': _token
            }
            $.ajax({
                url: 'ajax/menu/createCatalogue',
                type: 'POST',
                data: option,
                dataType: 'json',
                success: function (res) {
                    if (res.code == 0) {
                        _form.find('.form-error').removeClass('error').addClass('form-success').html(res.message);
                        const menuCatalogueSelect = $('select[name=menu_catalogue_id]');
                        console.log(menuCatalogueSelect);
                        menuCatalogueSelect.append(
                            `<option value="${res.data.id}">${res.data.title}</option>`
                        );
                    }
                    else {
                        _form.find('.form-success').removeClass('form-success').addClass('error').html(res.message);
                    }

                },
                beforeSend: function () {
                    _form.find('.error').html('');
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    if (jqXHR.status === 422) {
                        let errors = jqXHR.responseJSON.errors
                        for (let field in errors) {
                            let errorMessage = errors[field]
                            errorMessage.forEach(function (message) {
                                $('.' + field).html(message)
                            })
                        }
                    }
                }
            })
        })

    }

    var createMenuRow = () => {
        doc.on('click', '.add-menu', function (e) {
            e.preventDefault()
            let _this = $(this)
            $('.menu-wrapper').find('.notification').hide()
            $('.menu-wrapper').append(menuRowHtml)

        })
    }

    var menuRowHtml = (option) => {
        let html
        let $row = $('<div>').addClass('row menu-item mb10 ' + option.canonical)
        const columns = [
            { class: 'col-lg-4', name: 'menu[name][]', value: option.name || '' },
            { class: 'col-lg-4', name: 'menu[canonical][]', value: option.canonical || '' },
            { class: 'col-lg-2', name: 'menu[order][]', value: 0 },

        ]
        columns.forEach(col => {
            let $col = $('<div>').addClass(col.class)
            let $input = $('<input>')
                .attr('type', 'text')
                .attr('value', col.value)
                .addClass('form-control')
                .attr('name', col.name)
            $col.append($input)
            $row.append($col)
        })
        let $removeCol = $('<div>').addClass('col-lg-2')
        let $removeRow = $('<div>').addClass('form-row text-center')
        let $a = $('<a>').addClass('delete-menu')
        let icon = $('<i>').addClass('fa fa-trash-o')
        let input = $('<input>').addClass('hidden').val(0).attr('name', ' menu[id][]')
        $a.append(icon)
        $removeRow.append($a)
        $removeCol.append($removeRow)
        $row.append($removeCol)
        $row.append(input)
        return $row
    }

    let currentModel = null;

    var getMenu = () => {
        doc.on('click', '.menu-module', function () {
            let _this = $(this)
            let model = _this.attr('data-model')

            let target = _this.parents('.panel-default').find('.menu-list')
            menuClass = checkMenuRowExist();
            sendAjaxGetMenu({
                model: model,
                target: target,
                menuClass: menuClass
            });




        })
    }

    var getPaginationMenu = () => {
        doc.on('click', '.page-link', function (e) {
            let _this = $(this)
            e.preventDefault()
            let model = _this.parents('.panel-collapse').attr('id')
            let page = _this.text().trim()

            let target = _this.parents('.menu-list')
            menuClass = checkMenuRowExist();
            sendAjaxGetMenu({
                model: model,
                target: target,
                menuClass: menuClass,
                page: page,
                keyword: currentKeyword
            });
        })
    }

    var sendAjaxGetMenu = (options) => {
        const {
            model,
            target,
            menuClass,
            page = 1,
            keyword = ''
        } = options;
        $.ajax({
            url: 'ajax/dashboard/getMenu',
            type: 'GET',
            data: {
                model: model,
                page: page,
                keyword: keyword
            },

            dataType: 'json',
            beforeSend: function () {
                $('.panel-default').find('.menu-list').html('<div class="loading">Đang tải...</div>')
            },
            success: function (res) {
                console.log(res)
                let html = ''
                for (let i = 0; i < res.data.length; i++) {
                    html += renderModelMenu(res.data[i], menuClass)
                }
                if (res.total > res.per_page)
                    html += menuLink(res.links).prop('outerHTML')
                target.html(html)
            },
            error: function (err) {
                console.error('Lỗi:', err)
            }
        })
    }

    var menuLink = (links) => {

        let paginationUl = $('<ul>').addClass('pagination');
        $.each(links, function (index, link) {
            let liClass = 'page-item';
            if (link.active) {
                liClass += ' active';
            } else if (!link.url) {
                liClass += ' disabled';
            }

            let li = $('<li>').addClass(liClass);

            if (link.label === 'pagination.previous') {
                let span = $('<span>')
                    .addClass('page-link')
                    .attr('aria-hidden', true)
                    .html('&laquo;');
                li.append(span);
            } else if (link.label === 'pagination.next') {
                let span = $('<span>')
                    .addClass('page-link')
                    .attr('aria-hidden', true)
                    .html('&raquo;');
                li.append(span);
            } else if (link.url) {
                let a = $('<a>')
                    .addClass('page-link')
                    .text(link.label)
                    .attr('href', link.url);
                li.append(a);
            }

            paginationUl.append(li);
        });
        let nav = $('<nav>').append(paginationUl)
        return nav
    }


    var renderModelMenu = (object, menuClass) => {
        console.log(menuClass.includes(object.canonical))
        return `
        <div class="m-item ${object.canonical}">
        <div class="uk-flex uk-flex-middle ml20 ">
                <input style="cursor:pointer" type="checkbox" class="item-input-checkbox choose-menu" value="${object.canonical}" name="" id="${object.canonical}" ${menuClass.includes(object.canonical) ? 'checked' : ''}>
                <label style="cursor:pointer" class="item-label" for="${object.canonical}">${object.name}</label>
            </div >
        </div >
        `;
    }


    var deleteMenuRow = () => {
        doc.on('click', '.delete-menu', function (e) {
            e.preventDefault()
            $(this).closest('.row').remove()
            checkMenuItemLength()
        })
    }

    var checkMenuItemLength = () => {
        if ($('.menu-item').length === 0) {
            $('.notice').show()
        }
    }

    var chooseMenu = () => {
        doc.on('click', '.choose-menu', function () {
            let _this = $(this)
            let canonical = _this.val()
            let name = _this.siblings('label').text()
            let $row = menuRowHtml({
                name: name,
                canonical: canonical
            })
            let isChecked = _this.prop('checked')
            if (isChecked === true) {
                $('.menu-wrapper').append($row).find('.notification').hide()
            } else {
                $('.menu-wrapper').find('.' + canonical).remove()
                checkMenuItemLength()
            }
        })
    }

    var checkMenuRowExist = () => {
        let menuRowClass = $('.menu-item').map(function () {
            let classList = $(this).attr('class').split(/\s+/);
            return classList[3] || null;
        }).get();
        return menuRowClass;
    }

    var searchMenu = () => {
        // Hàm debounce
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

        // Hàm xử lý tìm kiếm
        const handleSearch = function () {
            let _this = $(this);
            currentKeyword = _this.val().trim();
            let option = {
                model: _this.parents('.panel-collapse').attr('id'),
                keyword: currentKeyword
            }

            let menuRow = checkMenuRowExist()
            let target = _this.parents().siblings('.menu-list');

            sendAjaxGetMenu({
                model: option.model,
                target: target,
                menuClass: menuRow,
                keyword: option.keyword
            });

        };

        // Áp dụng debounce với delay 300ms
        doc.on('keyup', '.search-menu', debounce(handleSearch, 300));
    };

    var setupNestable = () => {
        if ($('#nestable2').length) {
            $('#nestable2').nestable({
                group: 1
            }).on('change', updateNestableOutput);
        }
    }

    var updateNestableOutput = (e) => {
        var list = $(e.currentTarget);
        output = $(list.data('output'));
        let json = window.JSON.stringify(list.nestable('serialize'));
        let catalogueId = list.closest('.ibox-content').data('catalogue-id');

        $.ajax({
            url: 'ajax/menu/drag',
            method: 'POST',
            data: {
                json: json,
                catalogueId: catalogueId,
                _token: _token
            },

        });
    }
    var collapseNestable = () => {
        $('#nestable-menu').on('click', function (e) {
            var target = $(e.target),
                action = target.data('action');
            if (action === 'expand-all') {
                $('.dd').nestable('expandAll');
            }
            if (action === 'collapse-all') {
                $('.dd').nestable('collapseAll');
            }
        });
    }







    doc.ready(function () {
        createMenuCatalogue();
        createMenuRow();
        deleteMenuRow();
        getMenu();
        chooseMenu();
        getPaginationMenu();
        searchMenu();
        setupNestable();        // Khởi tạo Nestable
        collapseNestable();
    });

})(jQuery);