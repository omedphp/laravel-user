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

namespace Omed\Laravel\User\Services;

use Illuminate\Contracts\Hashing\Hasher as HasherContract;
use Omed\Component\User\Model\UserInterface;
use Omed\Component\User\Util\PasswordUpdater as BasePasswordUpdater;

class PasswordUpdater extends BasePasswordUpdater implements HasherContract
{
    /**
     * @var UserInterface
     */
    private $user;

    public function info($hashedValue)
    {
        return password_get_info($hashedValue);
    }

    /**
     * @param string $value
     * @param array  $options
     *
     * @return string|void
     */
    public function make($value, array $options = [])
    {
        return $this->hash($value, $options);
    }

    public function check($value, $hashedValue, array $options = [])
    {
        if (0 === \strlen($hashedValue)) {
            return false;
        }

        return password_verify($value, $hashedValue);
    }

    public function needsRehash($hashedValue, array $options = [])
    {
        return false;
    }
}
