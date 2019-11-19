<?php
namespace Domain\Model\ValueObject;

class RoverId
{
    private $id;

    public function __construct($id = null)
    {
        $this->id = $id ?: uniqid();
    }

    public function id(): string
    {
        return $this->id;
    }

    public function equals(RoverId $roverId): bool
    {
        return $this->id() === $roverId->id();
    }

}