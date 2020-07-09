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

use Tests\Omed\Laravel\User\UserTestCase;

class AuthControllerTest extends UserTestCase
{
    public function testLogin()
    {
        $this->generateUserData();
        $auth = config('auth');
        $response = $this->json('post', route('omed.auth.login'), [
            'email' => 'test@example.com',
            'password' => 'test',
        ]);
        $response->assertStatus(200);
        $this->assertNotNull($response->json('token'));
        $this->assertNotNull($response->json('type'));
        $this->assertNotNull($response->json('expires'));
    }
}
