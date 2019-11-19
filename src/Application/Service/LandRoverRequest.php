<?php

namespace Application\Service;

use Application\Contract\ApplicationRequestInterface;

class LandRoverRequest implements ApplicationRequestInterface
{
    private $roverX;
    private $roverY;
    private $orientation;
    private $landingAreaLength;
    private $landingAreaWidth;

    public function __construct(int $roverX, int $roverY, string $orientation, string $landingAreaLength, string $landingAreaWidth)
    {
        $this->roverX = $roverX;
        $this->roverY = $roverY;
        $this->orientation = $orientation;
        $this->landingAreaLength = $landingAreaLength;
        $this->landingAreaWidth = $landingAreaWidth;
    }

    public function roverX(): int
    {
        return $this->roverX;
    }

    public function roverY(): int
    {
        return $this->roverY;
    }

    public function orientation(): string
    {
        return $this->orientation;
    }

    public function landingAreaLength(): int
    {
        return $this->landingAreaLength;
    }

    public function landingAreaWidth(): int
    {
        return $this->landingAreaWidth;
    }

    public static function fromString(string $string): LandRoverRequest
    {
        $position = explode(' ', $string);
        return new self(
            (int)$position[0],
            (int)$position[1],
            $position[2],
            (int)$position[3],
            (int)$position[4]
        );
    }
}