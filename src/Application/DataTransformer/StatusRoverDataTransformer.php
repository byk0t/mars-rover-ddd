<?php
namespace Application\DataTransformer;

use Application\Contract\RoverDataTransformerInterface;
use Domain\Model\Entity\Rover;

class StatusRoverDataTransformer implements RoverDataTransformerInterface
{
    /**
     * @var Rover $rover
     */
    private $rover;

    public function write(Rover $rover)
    {
        $this->rover = $rover;
    }

    public function read()
    {
        $position = $this->rover->position();
        return
            $position->location()->x().' '.
            $position->location()->y().' '.
            $position->orientation()->orientation();
    }
}