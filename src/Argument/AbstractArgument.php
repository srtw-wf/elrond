<?php

namespace Elrond\Argument;

abstract class AbstractArgument
{
    /**
     * @var bool
     */
    private $isRequired;

    /**
     * @var int
     */
    private $position;

    /** @var mixed */
    private $value;

    public function __construct($value = null, bool $isRequired = false)
    {
        $this
            ->setValue($value)
            ->setIsRequired($isRequired);
    }

    public function setIsRequired(bool $isRequired): self
    {
        $this->isRequired = $isRequired;

        return $this;
    }

    public function getIsRequired(): bool
    {
        return $this->isRequired;
    }

    public function setValue($value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getValue()
    {
        return $this->value;
    }
}
