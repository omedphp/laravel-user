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

use Doctrine\Persistence\ObjectManager;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\ServiceProvider;
use Kilip\SanctumORM\Contracts\SanctumUserInterface;
use LaravelDoctrine\ORM\IlluminateRegistry;
use Omed\Component\User\Manager\UserManagerInterface;
use Omed\Component\User\UserComponent;
use Omed\Laravel\User\Http\Controllers\UserController;
use Omed\Laravel\User\Model\User;
use Omed\Laravel\User\Services\PasswordUpdater;
use Omed\Laravel\User\Services\UserManager;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;

class UserServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if (\function_exists('config_path')) {
            // function not available and 'publish' not relevant in Lumen
            $this->publishes([
                __DIR__.'/Resources/config/user.php' => config_path('omed/user.php'),
            ], 'config');
        }

        $app = $this->app;
        $this->loadRoutesFrom(__DIR__.'/Resources/routes/routes.php');

        $app->bind(PasswordUpdater::class, function ($app) {
            $encoderFactory = new EncoderFactory(['user' => [
                'algorithm' => 'bcrypt',
                'cost' => 12,
            ]]);

            return new PasswordUpdater($encoderFactory);
        });

        $app->singleton(UserManager::class, function (Application $app) {
            /** @var string $userModel */
            $userModel = config('omed.user.models.user');
            /** @var IlluminateRegistry $registry */
            $registry = $app->get('registry');

            /** @var ObjectManager $om */
            $om = $registry->getManagerForClass($userModel);

            return new UserManager($om);
        });

        $app->alias(UserManager::class, 'omed.managers.user');
        $app->alias(UserManager::class, UserManagerInterface::class);

        Hash::extend('omed_encryption', function (Application $app) {
            return $app->get(PasswordUpdater::class);
        });
        config(['hashing.driver' => 'omed_encryption']);

        $app->alias(UserController::class, 'OmedUserController');
    }

    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/Resources/config/user.php',
            'omed.user'
        );

        $this->configureDoctrine();
    }

    public function provides()
    {
        return [
            UserManagerInterface::class,
        ];
    }

    public static function getDoctrineXMLSchemaPath()
    {
        return __DIR__.'/Resources/config/doctrine';
    }

    private function configureDoctrine()
    {
        /** @var \Illuminate\Config\Repository $config */
        $config = $this->app['config'];

        $mappings = [
            'Omed\\Component\\User\\Model' => [
                'type' => 'xml',
                'dir' => UserComponent::getDoctrineXMLSchemaPath(),
            ],
            'Omed\\Laravel\\User\\Model' => [
                'type' => 'xml',
                'dir' => self::getDoctrineXMLSchemaPath(),
            ],
        ];

        $configKey = 'doctrine.managers.'.config('omed.user.manager_name', 'default').'.mappings';

        config([
            $configKey => array_merge(
                $mappings,
                config($configKey, [])
            ),
            'doctrine.resolve_target_entities' => array_merge(
                [
                    SanctumUserInterface::class => User::class,
                ],
                config('doctrine.resolve_target_entities', [])
            ),
        ]);

        $config->set('auth.providers.users.model', User::class);
        $config->set('auth.providers.users.driver', 'doctrine');
    }
}
