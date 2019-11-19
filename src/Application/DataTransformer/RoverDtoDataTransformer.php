<?php
namespace Application\DataTransformer;

use Application\Contract\RoverDataTransformerInterface;
use Domain\Model\Entity\Rover;

class RoverDtoDataTransformer implements RoverDataTransformerInterface
{
    /**
     * @var Rover $rover
     */
    private $rover;

    public function write(Rover $rover)
    {
        $this->rover = $rover;
        return $this;
    }

    public function read()
    {
        return [
            'id' => $this->rover->id()->id(),
            'x' => $this->rover->position()->location()->x(),
            'y' => $this->rover->position()->location()->y(),
            'orientation' => $this->rover->position()->orientation()->orientation(),
            'landed' => $this->rover->landed()
        ];
    }
}