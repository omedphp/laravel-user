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

use Doctrine\ORM\Event\PreFlushEventArgs;
use Omed\Component\User\Model\User;
use Omed\Laravel\User\Services\UserManager;

class UserChangedListener
{
    /**
     * @var UserManager
     */
    private $manager;

    public function __construct(UserManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param User              $user
     * @param PreFlushEventArgs $args
     */
    public function preFlushHandler(User $user, PreFlushEventArgs $args)
    {
        /** @var \Omed\Laravel\User\Services\UserManager $manager */
        $manager = $this->manager;
        $manager->updateCanonicalFields($user);
        $manager->updatePassword($user);
    }
}
