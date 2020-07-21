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

use Kilip\SanctumORM\Manager\TokenManagerInterface;
use Omed\Laravel\User\Services\UserManager;

trait UserManagerTrait
{
    /**
     * @return UserManager
     */
    public function getUserManager()
    {
        return app()->make('omed.managers.user');
    }

    /**
     * @return TokenManagerInterface
     */
    public function getTokenManager()
    {
        return app()->get(TokenManagerInterface::class);
    }
}
