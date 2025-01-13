<?php

return [
    'module' => [
        [
            'title' => 'User Management',
            'icon' => '<i class="fa fa-users"></i>',
            'name' => ['user'],
            'subModule' => [
                [
                    'title' => 'Member Management',
                    'route' => 'user.index'
                ],
                [
                    'title' => 'Member Group Management',
                    'route' => 'user.catalogue.index'
                ]
            ]
        ],
        [
            'title' => 'Post Management',
            'icon' => '<i class="fa fa-file-text"></i>',
            'name' => ['post'],
            'subModule' => [
                [
                    'title' => 'Post Group Management',
                    'route' => 'post.catalogue.index'
                ],
                [
                    'title' => 'Post Management',
                    'route' => 'post.index'
                ],
            ]
        ],
        [
            'title' => 'General Configuration',
            'icon' => '<i class="fa fa-cog"></i>',
            'name' => ['language', 'system'],
            'subModule' => [
                [
                    'title' => 'Language Management',
                    'route' => 'language.index'
                ]
            ]
        ]
    ]
];