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

namespace Omed\Laravel\User\Listener;

use Illuminate\Events\Dispatcher;
use Omed\Component\User\Manager\UserManagerInterface;
use Omed\Laravel\User\Model\User;
use Omed\Laravel\User\UserEvent;

class SecurityEventSubscriber
{
    /**
     * @var UserManagerInterface
     */
    private $userManager;

    public function __construct(UserManagerInterface $userManager)
    {
        $this->userManager = $userManager;
    }

    public function handleLogin(User $user)
    {
        $user->setLastLogin(new \DateTime());
        $this->store($user);
    }

    public function handleLogout()
    {
    }

    /**
     * @param Dispatcher $events
     */
    public function subscribe(Dispatcher $events)
    {
        $events->listen(UserEvent::LOGGED_IN, [$this, 'handleLogin']);
        $events->listen(UserEvent::LOGGED_OUT, [$this, 'handleLogout']);
    }

    private function store(User $user)
    {
        $manager = $this->userManager;
        $manager->storeUser($user);
    }
}
