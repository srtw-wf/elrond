<?php

namespace Elrond\Terminal;

class Terminal
{
    private $numberOfLines;

    private $numberOfCols;

    public function __construct()
    {
        $this->setDimensions();
    }

    public function pl(string $str)
    {
        echo $str . PHP_EOL;
    }

    private function setDimensions(): self
    {
        $this->numberOfLines = exec('tput lines');
        $this->numberOfCols = exec('tput cols');

        return $this;
    }

    public function getNumberOfCols(): int
    {
        return $this->numberOfCols;
    }

    public function getNumberOfLines(): int
    {
        return $this->numberOfLines;
    }
}
