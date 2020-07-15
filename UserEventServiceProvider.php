<?php


namespace Omed\Laravel\User;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as BaseEventServiceProvider;
use Omed\Laravel\User\Listener\AuthEventSubscriber;

class UserEventServiceProvider extends BaseEventServiceProvider
{
    protected $subscribe = [
        AuthEventSubscriber::class
    ];
}