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

namespace Tests\Omed\Laravel\User\Services;

use Omed\Laravel\User\Services\PasswordUpdater;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Tests\Omed\Laravel\User\UserTestCase as TestCase;

class PasswordUpdaterTest extends TestCase
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|EncoderFactoryInterface
     */
    private $encoder;

    /**
     * @var PasswordUpdater
     */
    private $target;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|PasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var string
     */
    private $hashed = '$2y$12$kgzGHn88HMhIA56ZSg0.LeJPES21fX1JuZFfHtOSEr.K.WJYtOnrm';

    protected function setUp(): void
    {
        parent::setUp();

        $this->encoder = $this->createMock(EncoderFactoryInterface::class);
        $this->passwordEncoder = $this->createMock(PasswordEncoderInterface::class);
        $this->target = new PasswordUpdater($this->encoder);
    }

    public function testInfo()
    {
        $target = $this->target;
        $hashed = $this->hashed;
        $info = $target->info($hashed);
        $this->assertEquals('bcrypt', $info['algoName']);
        $this->assertEquals(12, $info['options']['cost']);
    }

    public function testMake()
    {
        $target = $this->target;
        $factory = $this->encoder;
        $encoder = $this->passwordEncoder;

        $factory->expects($this->once())
            ->method('getEncoder')
            ->with('user')
            ->willReturn($encoder);
        $encoder->expects($this->once())
            ->method('encodePassword')
            ->willReturn('encoded');

        $this->assertEquals('encoded', $target->make('password'));
    }

    public function testCheck()
    {
        $target = $this->target;
        $hashed = $this->hashed;

        $this->assertFalse($target->check('password', ''));
        $this->assertTrue($target->check('test', $hashed));
    }

    public function testNeedsRehash()
    {
        $this->assertFalse($this->target->needsRehash($this->hashed));
    }
}
