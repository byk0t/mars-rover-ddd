<?php
namespace Infrastructure\Persistence\InMemory;

use Domain\Model\Contract\RoverRepositoryInterface;
use Domain\Model\Entity\Rover;
use Domain\Model\ValueObject\Position;
use Domain\Model\ValueObject\RoverId;

class InMemoryRoverRepository implements RoverRepositoryInterface
{
    /**
     * @var $rovers Rover[]
     */
    private $rovers = [];

    public function add(Rover $rover): void
    {
        $this->rovers[$rover->id()->id()] = $rover;
    }

    public function remove(Rover $rover): void
    {
        unset($this->rovers[$rover->id()->id()]);
    }

    public function ofPosition(Position $position): ?Rover
    {
        foreach ($this->rovers as $r) {
            if($r->position()->location()->equals($position->location())) {
                return $r;
            }
        }
        return null;
    }

    public function ofId(RoverId $roverId): ?Rover
    {
        return isset($this->rovers[$roverId->id()]) ? $this->rovers[$roverId->id()] : null;
    }

    public function all(): array
    {
        return $this->rovers;
    }

    public function allLanded(): array
    {
        $rovers = $this->all();
        foreach ($rovers as $id => $rover) {
            if(!$rover->landed()) {
                unset($rovers[$id]);
            }
        }
        return $rovers;
    }

    public function nextIdentity(): RoverId
    {
        return new RoverId();
    }
}