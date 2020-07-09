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

namespace Omed\Laravel\User\Model;

use Illuminate\Contracts\Auth\Authenticatable as AuthenticableInterface;
use LaravelDoctrine\ORM\Auth\Authenticatable as AuthenticableTrait;
use Omed\Component\User\Model\User as BaseUser;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends BaseUser implements AuthenticableInterface, JWTSubject
{
    use AuthenticableTrait;

    public function getJWTIdentifier()
    {
        return $this->getId();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
