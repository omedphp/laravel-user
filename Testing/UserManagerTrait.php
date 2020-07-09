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

namespace Omed\Laravel\User\Testing;

use Omed\Component\User\Model\UserInterface;
use Omed\Laravel\User\Services\UserManager;
use Tymon\JWTAuth\JWT;

trait UserManagerTrait
{
    /**
     * @param UserInterface $user
     *
     * @return string|null
     */
    public function generateToken(UserInterface $user)
    {
        $jwt = app()->get(JWT::class);

        return $jwt->fromUser($user);
    }

    /**
     * @return UserManager
     */
    public function getUserManager()
    {
        return app()->get('omed.managers.user');
    }
}
