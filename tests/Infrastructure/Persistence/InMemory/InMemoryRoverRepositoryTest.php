<?php
declare(strict_types=1);

namespace Infrastructure\Persistence\InMemory;

use Domain\Model\Entity\LandingArea;
use Domain\Model\Entity\Rover;
use Domain\Model\ValueObject\Location;
use Domain\Model\ValueObject\Orientation;
use Domain\Model\ValueObject\Position;
use PHPUnit\Framework\TestCase;

class InMemoryRoverRepositoryTest extends TestCase
{
    private $repository;
    private $dummyRover;
    private $dummyPosition;
    private $dummyLandingArea;

    protected function setUp(): void
    {
        $this->repository = new InMemoryRoverRepository();

        $this->dummyPosition = new Position(
            new Location(1,2),
            new Orientation('N')
        );

        $this->dummyLandingArea = new LandingArea(
            new Location(5, 5)
        );

        $this->dummyRover = new Rover(
            $this->repository->nextIdentity(),
            $this->dummyPosition,
            $this->dummyLandingArea
        );
    }

    public function testAdd(): void
    {
        $roverId = $this->dummyRover->id();
        $this->repository->add($this->dummyRover);
        $rover = $this->repository->ofId($roverId);
        $this->assertInstanceOf(Rover::class, $rover);
        $rovers = $this->repository->all();
        $this->assertCount(1, $rovers);
    }

    public function testRemove(): void
    {
        $roverId = $this->dummyRover->id();
        $this->repository->add($this->dummyRover);
        $this->repository->remove($this->dummyRover);
        $rover = $this->repository->ofId($roverId);
        $this->assertNull($rover);
        $rovers = $this->repository->all();
        $this->assertCount(0, $rovers);
    }

    public function testOfId(): void
    {
        $roverId = $this->dummyRover->id();
        $this->repository->add($this->dummyRover);
        $rover = $this->repository->ofId($roverId);
        $this->assertEquals($roverId->id(), $rover->id()->id());
    }

    public function testOfPosition(): void
    {
        $roverId = $this->dummyRover->id();
        $this->repository->add($this->dummyRover);
        $rover = $this->repository->ofPosition($this->dummyPosition);
        $this->assertEquals($roverId->id(), $rover->id()->id());
    }

}