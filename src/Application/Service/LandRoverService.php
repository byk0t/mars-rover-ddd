<?php
namespace Application\Service;

use Application\Contract\ApplicationRequestInterface;
use Application\Contract\ApplicationServiceInterface;
use Application\Contract\RoverDataTransformerInterface;
use Domain\Model\Entity\LandingArea;
use Domain\Model\Entity\Rover;
use Domain\Model\Contract\RoverRepositoryInterface;
use Domain\Model\ValueObject\Location;
use Domain\Model\ValueObject\Orientation;
use Domain\Model\ValueObject\Position;

class LandRoverService implements ApplicationServiceInterface
{
    /**
     * @var RoverRepositoryInterface
     */
    private $roverRepository;

    /**
     * @var RoverDataTransformerInterface
     */
    private $roverDataTransformer;

    public function __construct(RoverRepositoryInterface $roverRepository, RoverDataTransformerInterface $roverDataTransformer)
    {
        $this->roverRepository = $roverRepository;
        $this->roverDataTransformer = $roverDataTransformer;
    }

    /**
     * @param LandRoverRequest $request
     * @return array
     */
    public function execute(ApplicationRequestInterface $request)
    {
        $position = new Position(
            new Location($request->roverX(), $request->roverY()),
            new Orientation($request->orientation())
        );

        $landingArea = new LandingArea(
            new Location($request->landingAreaLength(), $request->landingAreaWidth())
        );

        $canBeLanded = true;

        $rover = $this->roverRepository->ofPosition($position);

        if($rover !== null) {
            $canBeLanded = false;
        }

        if(!$landingArea->inBoundaries($position->location())) {
            $canBeLanded = false;
        }

        $newRover = new Rover($this->roverRepository->nextIdentity(), $position, $landingArea);

        if($canBeLanded) {
            $newRover->land();
        }

        $this->roverRepository->add($newRover);

        $this->roverDataTransformer->write($newRover);

        return $this->roverDataTransformer->read();
    }
}