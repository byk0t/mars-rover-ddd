<?php
namespace Application\Contract;

use Domain\Model\Entity\Rover;

interface RoverDataTransformerInterface
{
    public function write(Rover $rover);
    public function read();
}