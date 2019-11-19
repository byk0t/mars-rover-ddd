<?php

namespace Domain\Model\Contract;

use Domain\Model\Entity\Rover;
use Domain\Model\ValueObject\Position;
use Domain\Model\ValueObject\RoverId;

interface RoverRepositoryInterface
{
    public function add(Rover $rover): void;
    public function remove(Rover $rover): void;
    public function ofPosition(Position $position): ?Rover;
    public function all(): array;
    public function allLanded(): array;
    public function ofId(RoverId $roverId): ?Rover;
    public function nextIdentity(): RoverId;
}