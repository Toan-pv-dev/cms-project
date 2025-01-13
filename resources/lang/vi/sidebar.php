<?php

return [
    'module' => [
        [
            'title' => 'Quản lý người dùng',
            'icon' => '<i class="fa fa-users"></i>',
            'name' => ['user'],
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

                ]

            ]
        ]
    ]





];