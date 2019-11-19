<?php


namespace Application\Service;


use Application\Contract\ApplicationRequestInterface;

class MoveRoverRequest implements ApplicationRequestInterface
{
    private $roverId;
    private $instructions;

    public function __construct(string $roverId, string $instructions)
    {
        $this->instructions = $instructions;
        $this->roverId = $roverId;
    }

    public function id(): string
    {
        return $this->roverId;
    }

    public function instructions(): string
    {
        return $this->instructions;
    }

    public static function fromString(string $string): MoveRoverRequest
    {
        $buffer = explode(' ', $string);
        return new self($buffer[0], $buffer[1]);
    }
}