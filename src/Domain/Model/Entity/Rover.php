<?php
namespace Domain\Model\Entity;

use Domain\Model\ValueObject\Instruction;
use Domain\Model\ValueObject\Position;
use Domain\Model\ValueObject\RoverId;

class Rover
{
    private $roverId;
    private $position;
    private $landingArea;
    private $landed = false;

    public function __construct(RoverId $roverId, Position $position, LandingArea $landingArea)
    {
        $this->roverId = $roverId;
        $this->position = $position;
        $this->landingArea = $landingArea;
    }

    public function id(): RoverId
    {
        return $this->roverId;
    }

    public function turnLeft(): void
    {
        $newOrientation = $this->position()->orientation()->turnLeft();
        $this->updatePosition(new Position($this->position->location(), $newOrientation));
    }

    public function turnRight(): void
    {
        $newOrientation = $this->position()->orientation()->turnRight();
        $this->updatePosition(new Position($this->position->location(), $newOrientation));
    }

    /**
     * Rotate Rover or return new possible Position
     * @param Instruction $instruction
     * @return Position|null
     */
    public function applyInstruction(Instruction $instruction): ?Position
    {
        $position = null;
        switch ($instruction->instruction()) {
            case Instruction::TURN_LEFT:
                $this->turnLeft();
                break;
            case Instruction::TURN_RIGHT:
                $this->turnRight();
                break;
            case Instruction::MOVE:
                $position = $this->nextMovePosition();
                break;
            default:
                break;
        }
        return $position;
    }

    public function position(): Position
    {
        return $this->position;
    }

    public function move(Position $position): void
    {
        $this->updatePosition($position);
    }

    public function land(): void
    {
        if($this->landingArea()->inBoundaries($this->position()->location())) {
            $this->landed = true;
        }
    }

    public function landed(): bool
    {
        return $this->landed;
    }

    public function landingArea(): LandingArea
    {
        return $this->landingArea;
    }

    private function updatePosition(Position $position): void
    {
        $this->position = $position;
    }

    /**
     * Return new Position if it fits landing area boundaries
     * @return Position|null
     */
    private function nextMovePosition(): ?Position
    {
        $newLocation = $this->position()->location()->move($this->position()->orientation());
        if($this->landingArea->inBoundaries($newLocation)) {
            return new Position($newLocation, $this->position()->orientation());
        }
        return null;
    }

}