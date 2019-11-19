<?php
namespace Domain\Model\ValueObject;

use Domain\Model\Exception\WrongArgumentException;

class Orientation
{
    private const NORTH = 'N';
    private const EAST  = 'E';
    private const SOUTH = 'S';
    private const WEST  = 'W';

    private const LEFT = 'L';
    private const RIGHT = 'R';

    private const AVAILABLE_ORIENTATIONS = [self::NORTH, self::EAST, self::SOUTH, self::WEST];

    private const MOVE_FACTORS = [
        self::NORTH => [0, 1],
        self::SOUTH => [0, -1],
        self::WEST  => [-1, 0],
        self::EAST  => [1, 0]
    ];

    private $orientation;

    public function __construct(string $orientation)
    {
        $orientation = strtoupper(trim($orientation));
        if(!in_array($orientation, self::AVAILABLE_ORIENTATIONS)) {
            throw new WrongArgumentException(
                sprintf(
                    "Available values for an orientation %s. Given %s",
                    implode(',', self::AVAILABLE_ORIENTATIONS), $orientation
                )
            );
        }
        $this->orientation = $orientation;
    }

    public function turnLeft(): Orientation
    {
        $index = array_search($this->orientation(), self::AVAILABLE_ORIENTATIONS);
        $nextIndex = $index ? $index - 1 : count(self::AVAILABLE_ORIENTATIONS) - 1;
        return new Orientation(self::AVAILABLE_ORIENTATIONS[$nextIndex]);
    }

    public function turnRight(): Orientation
    {
        $index = array_search($this->orientation(), self::AVAILABLE_ORIENTATIONS);
        $nextIndex = ($index + 1) == count(self::AVAILABLE_ORIENTATIONS) ? 0 : $index + 1;
        return new Orientation(self::AVAILABLE_ORIENTATIONS[$nextIndex]);
    }

    public function moveFactorX(): int
    {
        return self::MOVE_FACTORS[$this->orientation()][0];
    }

    public function moveFactorY(): int
    {
        return self::MOVE_FACTORS[$this->orientation()][1];
    }

    public function orientation(): string
    {
        return $this->orientation;
    }

    public function equals(Orientation $orientation): bool
    {
        return $this->orientation === $orientation->orientation();
    }
}