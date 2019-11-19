<?php
namespace Application\Service;

use Application\Contract\ApplicationRequestInterface;
use Application\Contract\ApplicationServiceInterface;
use Application\Contract\RoverDataTransformerInterface;
use Domain\Model\Contract\RoverRepositoryInterface;

class LandedRoversStatusService implements ApplicationServiceInterface
{
    private $roverRepository;
    private $roverDataTransformer;

    public function __construct(RoverRepositoryInterface $roverRepository, RoverDataTransformerInterface $roverDataTransformer)
    {
        $this->roverRepository = $roverRepository;
        $this->roverDataTransformer = $roverDataTransformer;
    }

    /**
     * @param LandedRoversStatusRequest $request
     * @return array
     */
    public function execute(ApplicationRequestInterface $request)
    {
        $rovers = $this->roverRepository->allLanded();
        $output = [];
        foreach ($rovers as $rover) {
            $this->roverDataTransformer->write($rover);
            $output[] = $this->roverDataTransformer->read();
        }
        return $output;
    }
}