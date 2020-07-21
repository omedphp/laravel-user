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

namespace Omed\Laravel\User\Http\Resources;

use Doctrine\Inflector\Inflector;
use Omed\Laravel\Core\Http\Resources\JsonResource as JsonResource;

class UserResource extends JsonResource
{
    protected $filters = [
        'password',
        'salt',
        'token',
        'identifier',
        'canonical',
    ];

    /**
     * @param object $resource
     * @return string
     */
    protected function getSelfRoute($resource)
    {
        return route('users.show', ['user' => $resource->getId()]);
    }
}
