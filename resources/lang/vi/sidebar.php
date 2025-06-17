<?php

return [
    'module' => [
        [
            'title' => 'Quản lý danh mục thuộc tính',
            'icon' => '<i class="fa fa-users"></i>',
            'name' => ['attribute', 'permission'],
            'subModule' => [

                [
                    'title' => 'Quản lý nhóm thuộc tính',
                    'route' => 'attribute.catalogue.index'
                ],

                [
                    'title' => 'Quản lý  thuộc tính',
                    'route' => 'attribute.index'
                ]

            ]
        ],
        [
            'title' => 'Quản lý danh muc sản phẩm',
            'icon' => '<i class="fa fa-users"></i>',
            'name' => ['product', 'permission'],
            'subModule' => [

                [
                    'title' => 'Quản lý nhóm sản phẩm',
                    'route' => 'product.catalogue.index'
                ],

                [
                    'title' => 'Quản lý sản phẩm',
                    'route' => 'product.index'
                ]

            ]
        ],
        [
            'title' => 'Quản lý người dùng',
            'icon' => '<i class="fa fa-users"></i>',
            'name' => ['user', 'permission'],
            'subModule' => [
                [
                    'title' => 'Quản lý thành viên',
                    'route' => 'user.index'

                ],
                [
                    'title' => 'Quản lý nhóm thành viên',
                    'route' => 'user.catalogue.index'
                ]

            ]
        ],
        [
            'title' => 'Quản lý bài viết',
            'icon' => '<i class="fa fa-file-text"></i>',
            'name' => ['post'],
            'subModule' => [
                [
                    'title' => 'Quản lý nhóm bài viết',
                    'route' => 'post.catalogue.index'

                ],
                [
                    'title' => 'Quản lý bài viết',
                    'route' => 'post.index'


                ],
                [
                    'title' => 'Quản lý quyền',
                    'route' => 'permission.index'
                ],


            ]
        ],

        [
            'title' => 'QL Menu',
            'icon' => '<i class="fa fa-bar"></i>',
            'name' => ['language', 'menu'],
            'subModule' => [
                [
                    'title' => 'Cài đặt Menu',
                    'route' => 'menu.index'

                ],


            ]
        ],

        [
            'title' => 'Cấu hình chung',
            'icon' => '<i class="fa fa-cog"></i>',
            'name' => ['language', 'system'],
            'subModule' => [
                [
                    'title' => 'Quản lý ngôn ngữ',
                    'route' => 'language.index'

                ],
                [
                    'title' => 'Quản lý Module',
                    'route' => 'generate.index'

                ],
                [
                    'title' => 'Cấu hình hệ thống',
                    'route' => 'system.index'

                ],


            ]
        ],
        [
            'title' => 'Quản lý Banner, Slide và Hình ảnh',
            'icon' => '<i class="fa fa-file-image-o"></i>',
            'name' => ['slide'],
            'subModule' => [
                [
                    'title' => 'Cài đặt Slide',
                    'route' => 'slide.index'

                ],

            ]
        ],

        [
            'title' => 'Quản lý Widget',
            'icon' => '<i class="fa fa-file-image-o"></i>',
            'name' => ['widget'],
            'subModule' => [
                [
                    'title' => 'Quản lý Widget',
                    'route' => 'widget.index'

                ],

            ]
        ],


    ]





];
