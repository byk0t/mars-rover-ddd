<?php
namespace Domain\Model\Entity;

use Domain\Model\ValueObject\Location;

class LandingArea
{
    private $bottomLeftLocation;
    private $topRightLocation;

    public function __construct(Location $location)
    {
        $this->bottomLeftLocation = new Location(0, 0);
        $this->topRightLocation = $location;
    }

    public function bottomLeftLocation(): Location
    {
        return $this->bottomLeftLocation;
    }

    public function topRightLocation(): Location
    {
        return $this->topRightLocation;
    }

    public function inBoundaries(Location $location): bool
    {
        $x = $location->x();
        $y = $location->y();

        return
            $x >= $this->bottomLeftLocation->x() && $x <= $this->topRightLocation->x() &&
            $y >= $this->bottomLeftLocation->y() && $y <= $this->topRightLocation->y();
    }

}