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

namespace Tests\Omed\Laravel\User\Controllers;

use Tymon\JWTAuth\JWT;
use Omed\Laravel\User\Testing\UserManagerTrait;
use Tests\Omed\Laravel\User\UserTestCase;

class UserControllerTest extends UserTestCase
{
    use UserManagerTrait;

    public function testUnauthenticatedUserAccess()
    {
        $response = $this->json('GET', route('omed_user.index'));
        $response->assertStatus(401);
    }

    public function testIndex()
    {
        $user = $this->generateUserData();
        $token = $this->generateToken($user);

        $response = $this->json('GET', route('omed_user.index'), [], [
            'Authorization' => 'Bearer '.$token,
        ]);

        $response->assertStatus(200);
    }
}
