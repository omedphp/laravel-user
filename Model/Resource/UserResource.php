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

namespace Omed\Laravel\User\Model\Resource;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        /** @var \Omed\Laravel\User\Model\User $resource */
        $resource = $this->resource;

        return [
            'id' => $resource->getId(),
            'username' => $resource->getUsername(),
            'email' => $resource->getEmail(),
            'created_at' => $resource->getCreatedAt(),
            'updated_at' => $resource->getUpdatedAt(),
            '_self' => route('omed_user.show', ['omed_user' => $resource->getId()]),
        ];
    }
}
