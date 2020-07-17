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

namespace Omed\Laravel\User;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as BaseEventServiceProvider;
use Omed\Laravel\User\Listener\SecurityEventSubscriber;

class SecurityEventServiceProvider extends BaseEventServiceProvider
{
    protected $subscribe = [
        SecurityEventSubscriber::class,
    ];
}
