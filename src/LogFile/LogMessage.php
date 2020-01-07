<?php

namespace Elrond\LogFile;

use DateTimeImmutable;

class LogMessage
{
    private $timestamp;

    private $message;

    public function __construct(string $logLine)
    {
        $json = json_decode($logLine, true);
        $this->setTimestamp($json['@timestamp']);
        $this->setMessage($json['@message']);

    }

    private function setTimestamp(string $dateTime): self
    {
        $this->timestamp = new DateTimeImmutable($dateTime);
        return $this;
    }

    public function getTimestamp(): DateTimeImmutable
    {
        return $this->timestamp;
    }

    private function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}
