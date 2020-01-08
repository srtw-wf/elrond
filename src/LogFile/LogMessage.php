<?php

namespace Elrond\LogFile;

use DateTimeImmutable;

class LogMessage
{
    private $timestamp;

    private $message;

    private $stackTrace = '';

    public function __construct(string $logLine)
    {
        $json = json_decode($logLine, true);
        $this
            ->setTimestamp($json['@timestamp'])
            ->setMessage($json['@message']);
        if (isset($json['@fields']['ctxt_trace'])) {
            $this->setStackTrace($json['@fields']['ctxt_trace']);
        }

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

    private function setStackTrace(string $stackTrace): self
    {
        $this->stackTrace = $stackTrace;

        return $this;
    }

    public function getStackTrace(): string
    {
        return $this->stackTrace;
    }

    public function hasStackTrace(): bool
    {
        return !empty($this->stackTrace);
    }
}
