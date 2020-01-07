<?php

namespace Elrond\Argument;

class FileArgument extends AbstractArgument
{
    public function fileExists(): bool
    {
        return file_exists($this->getValue());
    }
}
