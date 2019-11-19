<?php
declare(strict_types=1);

namespace Application\Service;

use Application\DataTransformer\RoverDtoDataTransformer;
use Domain\Model\Entity\LandingArea;
use Domain\Model\Entity\Rover;
use Domain\Model\ValueObject\Location;
use Domain\Model\ValueObject\Orientation;
use Domain\Model\ValueObject\Position;
use Infrastructure\Persistence\InMemory\InMemoryRoverRepository;
use PHPUnit\Framework\TestCase;

class MoveRoverServiceTest extends TestCase
{

    private $roverRepository;
    private $firstRover;
    private $secondRover;
    private $instructions;
    private $moveRoverService;
    private $firstRoverId;
    private $secondRoverId;
    private $landingArea;

    protected function setUp(): void
    {
        $this->roverRepository = new InMemoryRoverRepository();
        $this->moveRoverService = new MoveRoverService($this->roverRepository, new RoverDtoDataTransformer());

        $this->landingArea = new LandingArea(new Location(5,5));

        $this->firstRover = new Rover(
            $this->roverRepository->nextIdentity(),
            new Position(new Location(1, 2), new Orientation('N')),
            $this->landingArea
        );
        $this->instructions = 'LMLMLMLMM';
        $this->roverRepository->add($this->firstRover);
        $this->firstRoverId = $this->firstRover->id()->id();
    }

    public function testSuccessfulMove(): void
    {
        $roverDto = $this->moveRoverService->execute(
            MoveRoverRequest::fromString($this->firstRoverId.' '.$this->instructions)
        );
        $this->assertEquals(1, $roverDto['x']);
        $this->assertEquals(3, $roverDto['y']);
        $this->assertEquals('N', $roverDto['orientation']);
    }

    public function testOutOfLandingAreaMoveAttempt(): void
    {
        $instructions = 'MMMMRM';
        $roverDto = $this->moveRoverService->execute(
            MoveRoverRequest::fromString($this->firstRoverId.' '.$instructions)
        );
        $this->assertEquals(2, $roverDto['x']);
        $this->assertEquals(5, $roverDto['y']);
        $this->assertNotEquals(6, $roverDto['y']);
        $this->assertEquals('E', $roverDto['orientation']);
    }

    public function testAlreadyOccupiedLocationMoveAttempt(): void
    {
        $this->moveRoverService->execute(
            MoveRoverRequest::fromString($this->firstRoverId.' '.$this->instructions)
        );

        $this->initSecondRover();

        $instructions = 'RRMMRM';
        $secondRoverDto = $this->moveRoverService->execute(
            MoveRoverRequest::fromString($this->secondRoverId.' '.$instructions)
        );

        $this->assertEquals(2, $secondRoverDto['x']);
        $this->assertEquals(4, $secondRoverDto['y']);
        $this->assertEquals('N', $secondRoverDto['orientation']);
    }

    private function initSecondRover(): void
    {
        $this->secondRover = new Rover(
            $this->roverRepository->nextIdentity(),
            new Position(new Location(3, 3), new Orientation('E')),
            $this->landingArea
        );
        $this->roverRepository->add($this->secondRover);
        $this->secondRoverId = $this->secondRover->id()->id();
    }

}