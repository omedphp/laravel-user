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

return [
    'route_prefix' => [
        'user' => 'users',
    ],
    'models' => [
        'user' => \Omed\Laravel\User\Model\User::class,
        'permission' => '',
        'role' => '',
    ],
    'manager_name' => 'default',
];
