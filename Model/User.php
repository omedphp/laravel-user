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

use DateTime;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticableInterface;
use LaravelDoctrine\Extensions\Timestamps\Timestamps;
use LaravelDoctrine\ORM\Auth\Authenticatable as AuthenticableTrait;
use Omed\Component\User\Model\User as BaseUser;
use Omed\Component\User\Model\UserInterface;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends BaseUser implements AuthenticableInterface, JWTSubject
{
    use AuthenticableTrait,Timestamps;

    /**
     * @var null|DateTime
     */
    protected $createdAt;

    /**
     * @var null|DateTime
     */
    protected $updatedAt;

    public function getJWTIdentifier()
    {
        return $this->getId();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * @return DateTime|null
     */
    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param DateTime|null $createdAt
     * @return User
     */
    public function setCreatedAt(?DateTime $createdAt): User
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTime|null $updatedAt
     * @return User
     */
    public function setUpdatedAt(?DateTime $updatedAt): User
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }
}
