<?php

return [
    'module' => [
        [
            'title' => 'Quản lý danh muc thuoc tinh',
            'icon' => '<i class="fa fa-users"></i>',
            'name' => ['product', 'attribute'],
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
            'title' => 'Quản lý danh muc san pham',
            'icon' => '<i class="fa fa-users"></i>',
            'name' => ['product', 'permission'],
            'subModule' => [

                [
                    'title' => 'Quản lý nhóm thành viên',
                    'route' => 'product.catalogue.index'
                ]

            ]
        ],
        [
            'title' => 'Quản lý san pham',
            'icon' => '<i class="fa fa-users"></i>',
            'name' => ['product', 'permission'],
            'subModule' => [

                [
                    'title' => 'Quản lý nhóm thành viên',
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

                ]

            ]
        ]
    ]





];
