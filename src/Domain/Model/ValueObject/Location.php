<?php
namespace Domain\Model\ValueObject;

class Location
{
    private $x;
    private $y;

    public function __construct(int $x, int $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    public function x(): int
    {
        return $this->x;
    }

    public function y(): int
    {
        return $this->y;
    }

    /**
     * We need to know orientation to make a move
     * @param Orientation $orientation
     * @return Location
     */
    public function move(Orientation $orientation): Location
    {
        $x = $this->x + $orientation->moveFactorX();
        $y = $this->y + $orientation->moveFactorY();
        return new Location($x, $y);
    }

    public function equals(Location $location): bool
    {
        return $this->x === $location->x() && $this->y === $location->y();
    }
}