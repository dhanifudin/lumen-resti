<?php

return [
    'resources' => [
        'users' => [
            'model' => 'App\User',
            'repository' => 'App\Repositories\UserRepository',
            'controller' => 'App\Http\Controllers\UserController',
            'routes' => [
                'custom' => [
                    'method' => 'GET',
                    'action' => 'sayHello',
                ]
            ]
        ]
    ]
];
