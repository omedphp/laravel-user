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
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\ServiceProvider;
use LaravelDoctrine\Extensions\Timestamps\TimestampableExtension;
use LaravelDoctrine\ORM\IlluminateRegistry;
use Omed\Component\User\Manager\UserManagerInterface;
use Omed\Laravel\Auth\Controller\AuthController;
use Omed\Laravel\User\Controllers\UserController;
use Omed\Laravel\User\Model\User;
use Omed\Laravel\User\Services\PasswordUpdater;
use Omed\Laravel\User\Services\UserManager;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;

class UserServiceProvider extends ServiceProvider
{
    public function boot(Filesystem $filesystem): void
    {
        if (\function_exists('config_path')) {
            // function not available and 'publish' not relevant in Lumen
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
            /** @var string $userModel */
            $userModel = config('omed_user.models.user');
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
        $app->alias(AuthController::class, 'OmedAuthController');
    }

    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/Resources/config/user.php',
            'omed_user'
        );
        $this->configureDoctrine();
    }

    public static function getDoctrineXMLSchemaPath()
    {
        return __DIR__.'/Resources/config/doctrine';
    }

    private function configureDoctrine()
    {
        /** @var \Illuminate\Config\Repository $config */
        $config = $this->app['config'];
        $managerConfig = config('omed_user.doctrine_manager_config');

        $config->set('auth.model', User::class);
        $config->set('auth.defaults.guard', 'api');
        $config->set('auth.guards.api.driver', 'jwt');
        $config->set('auth.providers.users.model', User::class);
        $config->set('auth.providers.users.driver', 'doctrine');
        $config->set('doctrine.managers.omed_user', $managerConfig);

        $extensions = $config->get('doctrine_extensions');
        $extensions = \is_array($extensions) ? $extensions : [];
        $extensions = array_merge($extensions, [
            TimestampableExtension::class,
        ]);
        $config->set('doctrine.extensions', $extensions);
    }
}
