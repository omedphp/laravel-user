<?php

/*
 * This file is part of the Omed project.
 *
 * (c) Anthonius Munthi <https://itstoni.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

use Omed\Component\User\UserComponent;
use Omed\Laravel\User\UserServiceProvider;

return [
    'models' => [
        'user' => \Omed\Laravel\User\Model\User::class,
        'permission' => '',
        'role' => '',
    ],

    'route_prefix' => [
        'users' => '/api/users',
    ],

    'table_names' => [
        'user' => 'omed_users',
    ],

    'jwt' => [
        'secret' => '',
    ],
    'doctrine_manager_config' => [
        'dev' => true,
        'meta' => 'simplified-xml',
        'connection' => 'sqlite',
        'namespaces' => [
            'Omed\\Component\\User',
            'Omed\\Laravel\\User',
        ],
        'proxies' => [
            'namespace' => false,
            'path' => storage_path('proxies'),
            'auto_generate' => env('DOCTRINE_PROXY_AUTOGENERATE', false),
        ],
        'paths' => [
            UserComponent::getDoctrineXMLSchemaPath() => 'Omed\\Component\\User\\Model',
            UserServiceProvider::getDoctrineXMLSchemaPath() => 'Omed\\Laravel\\User\\Model',
        ],
    ],
];
