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

namespace Omed\Laravel\User\Tests\Controllers;

use Omed\Laravel\User\Testing\UserManagerTrait;
use Omed\Laravel\User\Tests\UserTestCase;

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
        $token = $this->getTokenManager()->createToken($user, 'phpunit');
        $response = $this->json('GET', route('omed_user.index'), [], [
            'Authorization' => 'Bearer '.$token->plainTextToken,
        ]);

        $response->assertStatus(200);
    }
}
