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

use Doctrine\Common\Persistence\ManagerRegistry;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\ServiceProvider;
use Omed\Laravel\User\Controllers\AuthController;
use Omed\Laravel\User\Controllers\UserController;
use Omed\Laravel\User\Services\PasswordUpdater;
use Omed\Laravel\User\Services\UserManager;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;

class UserServiceProvider extends ServiceProvider
{
    public function boot(Filesystem $filesystem): void
    {
        if (\function_exists('config_path')) { // function not available and 'publish' not relevant in Lumen
            $this->publishes([
                __DIR__.'/Resources/config/user.php' => config_path('omed_user.php'),
            ], 'config');
        }

        $app = $this->app;

        $this->loadRoutesFrom(__DIR__.'/Resources/config/routes.php');

        $app->bind(PasswordUpdater::class, function ($app) {
            $encoderFactory = new EncoderFactory(['user' => [
                'algorithm' => 'bcrypt',
                'cost' => 12,
            ]]);

            return new PasswordUpdater($encoderFactory);
        });

        $app->bind(UserManager::class, function (Application $app) {
            /* @var string $userModel */
            $userModel = config('omed_user.models.user');
            /** @var \Doctrine\Common\Persistence\ManagerRegistry $registry */
            $registry = $app->get(ManagerRegistry::class);

            /* @var \Doctrine\Common\Persistence\ObjectManager $om */
            $om = $registry->getManagerForClass($userModel);

            return new UserManager($om);
        });
        $app->alias(UserManager::class, 'omed.managers.user');

        Hash::extend('omed_encryption', function (Application $app) {
            return $app->get(PasswordUpdater::class);
        });
        config(['hashing.driver' => 'omed_encryption']);

        $app->alias(UserController::class, 'OmedUserController');
        $app->alias(AuthController::class, 'OmedAuthController');
    }

    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/Resources/config/user.php',
            'omed_user'
        );

        $config = config('omed_user.doctrine_manager_config');
        $this->app['config']->set('doctrine.managers.omed_user', $config);
    }

    public static function getDoctrineXMLSchemaPath()
    {
        return __DIR__.'/Resources/config/doctrine';
    }
}
