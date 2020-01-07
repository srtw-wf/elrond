<?php

namespace Elrond\LogFile;

class LogFile
{
    /**
     * @var string
     */
    private $fileName;

    public function __construct(string $fileName)
    {
        $this->setFileName($fileName);
    }

    /**
     * @param string $fileName
     *
     * @return $this
     */
    public function setFileName(string $fileName): self
    {
        $this->fileName = $fileName;

        return $this;
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return $this->fileName;
    }

    /**
     * @param int $x
     *
     * @return array
     */
    public function getLastXLines(int $x): array
    {
        $result = [];
        $fh = fopen($this->getFileName(), 'r');

        $cursor = -1;

        for ($i = 0; $i < $x; $i++) {
            $line = $this->readToStartOfLine($fh, $cursor);
            if (!empty($line)) {
                $result[] = $line;
            } else {
                continue;
            }
        }

        fclose($fh);
        $result = array_reverse($result);
        return $result;
    }

    /**
     * @param $filePointer
     * @param int $cursorPosition
     *
     * @return string
     */
    private function readToStartOfLine($filePointer, int &$cursorPosition = -1): string
    {
        $char = '';
        $line = '';
        while ($char !== PHP_EOL && $char !== false) {
            fseek($filePointer, $cursorPosition--, SEEK_END);
            $char = fgetc($filePointer);
            if ($char !== PHP_EOL && $char !== false) {
                $line = $char . $line;
            }
        }

        return $line;
    }
}
