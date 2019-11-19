<?php
namespace Domain\Model\ValueObject;

use Domain\Model\Exception\WrongArgumentException;

class Instruction
{
    public const MOVE = 'M';
    public const TURN_LEFT = 'L';
    public const TURN_RIGHT = 'R';

    private const AVAILABLE_INSTRUCTIONS = [self::MOVE, self::TURN_LEFT, self::TURN_RIGHT];

    private $instruction = '';

    public function __construct($instruction)
    {
        $instruction = strtoupper(trim($instruction));
        if(!in_array($instruction, self::AVAILABLE_INSTRUCTIONS)) {
            throw new WrongArgumentException(
                sprintf(
                    "Available values for an instruction %s. Given %s",
                    implode(',', self::AVAILABLE_INSTRUCTIONS), $instruction
                )
            );
        }
        $this->instruction = $instruction;
    }

    public function instruction(): string
    {
        return $this->instruction;
    }

    public function equals(Instruction $instruction)
    {
        return $this->instruction() === $instruction->instruction();
    }
}