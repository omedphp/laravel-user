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

namespace Tests\Omed\Laravel\User\Model;

use Omed\Laravel\User\Model\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    /**
     * @var User
     */
    private $target;

    protected function setUp(): void
    {
        parent::setUp();

        $this->target = new User();
    }

    public function testCreatedAt()
    {
        $target = $this->target;
        $dateTime = $this->createMock(\DateTime::class);

        $this->assertNull($target->getCreatedAt());
        $target->setCreatedAt($dateTime);
        $this->assertSame($dateTime, $target->getCreatedAt());
    }

    public function testUpdatedAt()
    {
        $target = $this->target;
        $dateTime = $this->createMock(\DateTime::class);

        $this->assertNull($target->getUpdatedAt());
        $target->setUpdatedAt($dateTime);
        $this->assertEquals($dateTime, $target->getUpdatedAt());
    }
}
