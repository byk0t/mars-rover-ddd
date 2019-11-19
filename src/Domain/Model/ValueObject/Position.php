<?php
namespace Domain\Model\ValueObject;

class Position
{
    private $location = null;
    private $orientation = null;

    public function __construct(Location $location, Orientation $orientation)
    {
        $this->location = $location;
        $this->orientation = $orientation;
    }

    public function location(): ?Location
    {
        return $this->location;
    }

    public function orientation(): ?Orientation
    {
        return $this->orientation;
    }

    public function equals(Position $position): bool
    {
        return
            $this->location()->equals($position->location()) &&
            $this->orientation()->equals($position->orientation);
    }
}