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

namespace Tests\Omed\Laravel\User;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Omed\Component\User\Manager\UserManager;
use Omed\Laravel\User\Model\User;

/**
 * Class UserServiceProviderTest.
 *
 * @covers \Omed\Laravel\User\UserServiceProvider
 */
class UserServiceProviderTest extends UserTestCase
{
    public function testBoot(): void
    {
        /** @var ManagerRegistry $registry */
        $registry = $this->app->get('registry');
        $om = $registry->getManagerForClass(User::class);
        $this->assertInstanceOf(ObjectManager::class, $om);
    }

    public function testUserManagerConfig()
    {
        $userManager = $this->app->get('omed.managers.user');
        $this->assertInstanceOf(UserManager::class, $userManager);
    }
}
