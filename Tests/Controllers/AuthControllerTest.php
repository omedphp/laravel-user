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
        $response = $this->json('post', route('omed.auth.login'), [
            'email' => 'test@example.com',
            'password' => 'test',
        ]);
        $response->assertStatus(200);
        $this->assertNotNull($response->json('token'));
        $this->assertNotNull($response->json('type'));
        $this->assertNotNull($response->json('expires'));
    }

    public function testFailedLogin()
    {
        $this->generateUserData();

        $response = $this->json('post', route('omed.auth.login'), [
            'email' => 'test@example.com',
            'password' => 'some',
        ]);

        $response->assertStatus(401);
        $this->assertEquals('Unauthorized', $response->json('error'));
    }

    public function testProfileLink()
    {
        $user = $this->generateUserData();
        $token = $this->generateToken($user);

        $response = $this->json('post', route('omed.auth.profile'), [], [
            'Authorization' => 'Bearer '.$token,
        ]);

        $json = $response->json();
        $data = $json['data'];
        $response->assertStatus(200);
        $this->assertEquals($user->getUsername(), $data['username']);
    }

    public function testLogout()
    {
        $user = $this->generateUserData();
        $token = $this->generateToken($user);

        $response = $this->json('post', route('omed.auth.logout'), [], [
            'Authorization' => 'Bearer '.$token,
        ]);

        $response->assertStatus(200);
        $this->assertEquals('Successfully logged out', $response->json('message'));
    }

    public function testRefresh()
    {
        $user = $this->generateUserData();
        $token = $this->generateToken($user);

        $response = $this->json('post', route('omed.auth.refresh'), [], [
            'Authorization' => 'Bearer '.$token,
        ]);

        $response->assertStatus(200);
        $this->assertNotEquals($token, $response->json('token'));
    }
}
