<?php

return [
    'module' => [
        [
            'title' => '用户管理',
            'icon' => '<i class="fa fa-users"></i>',
            'name' => ['user'],
            'subModule' => [
                [
                    'title' => '会员管理',
                    'route' => 'user.index'
                ],
                [
                    'title' => '会员组管理',
                    'route' => 'user.catalogue.index'
                ]
            ]
        ],
        [
            'title' => '文章管理',
            'icon' => '<i class="fa fa-file-text"></i>',
            'name' => ['post'],
            'subModule' => [
                [
                    'title' => '文章组管理',
                    'route' => 'post.catalogue.index'
                ],
                [
                    'title' => '文章管理',
                    'route' => 'post.index'
                ]
            ]
        ],
        [
            'title' => '通用设置',
            'icon' => '<i class="fa fa-cog"></i>',
            'name' => ['language', 'system'],
            'subModule' => [
                [
                    'title' => '语言管理',
                    'route' => 'language.index'
                ]
            ]
        ]
    ]
];
