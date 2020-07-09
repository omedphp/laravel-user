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

use LaravelDoctrine\ORM\DoctrineServiceProvider;
use LaravelDoctrine\ORM\Facades\Doctrine;
use LaravelDoctrine\ORM\Facades\EntityManager;
use LaravelDoctrine\ORM\Facades\Registry;
use Omed\Component\User\Model\UserInterface;
use Omed\Laravel\Core\CoreServiceProvider;
use Omed\Laravel\User\Model\User;
use Omed\Laravel\User\Testing\UserManagerTrait;
use Omed\Laravel\User\UserServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Tymon\JWTAuth\Providers\LaravelServiceProvider as JWTAuthServiceProvider;

class UserTestCase extends OrchestraTestCase
{
    use UserManagerTrait;

    protected function refreshDatabase()
    {
        $registry = app()->get('registry');
        $this->artisan('doctrine:schema:create');
    }

    protected function getPackageProviders($app)
    {
        return [
            JWTAuthServiceProvider::class,
            DoctrineServiceProvider::class,
            CoreServiceProvider::class,
            UserServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Registry' => Registry::class,
            'EntityManager' => EntityManager::class,
            'Doctrine' => Doctrine::class,
            'JWTAuth' => 'Tymon\JWTAuth\Facades\JWTAuth',
            'JWTFactory' => 'Tymon\JWTAuth\Facades\JWTFactory',
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $defPath = sys_get_temp_dir().'/omed-user/foo-model';
        if (!is_dir($defPath)) {
            mkdir($defPath, 0777, true);
        }
        $app['config']->set('doctrine.managers.default.connection', 'sqlite');
        $app['config']->set('doctrine.managers.default.paths', [$defPath]);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->refreshDatabase();
    }

    /**
     * @return UserInterface
     */
    protected function generateUserData()
    {
        $manager = $this->getUserManager();
        $user = $manager->createUser();
        $user
            ->setUsername('test')
            ->setPlainPassword('test')
            ->setEmail('test@example.com');
        $manager->storeUser($user);

        return $user;
    }
}
