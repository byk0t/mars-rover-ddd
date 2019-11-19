<?php
declare(strict_types=1);

namespace Domain\Model\Entity;

use Domain\Model\ValueObject\Location;
use Domain\Model\ValueObject\Orientation;
use Domain\Model\ValueObject\Position;
use Domain\Model\ValueObject\RoverId;
use PHPUnit\Framework\TestCase;

class RoverTest extends TestCase
{
    protected function setUp(): void
    {
    }

    public function testCanBeCreated(): void
    {
        $rover = new Rover(
            new RoverId(),
            new Position(
                new Location(1,2),
                new Orientation('N')
            ),
            new LandingArea(
                new Location(5, 5)
            )
        );

        $this->assertInstanceOf(Rover::class, $rover);
    }
}
