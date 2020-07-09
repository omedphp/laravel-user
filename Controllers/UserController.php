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

namespace Omed\Laravel\User\Controllers;

use Omed\Laravel\User\Model\Resource\UserResource;
use Omed\Laravel\User\Services\UserManager;

class UserController extends Controller
{
    /**
     * @param UserManager $manager
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(UserManager $manager)
    {
        $collection = $manager->findAll();

        return UserResource::collection($collection);
    }
}
