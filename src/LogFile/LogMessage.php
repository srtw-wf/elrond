<?php

namespace Elrond\LogFile;

use DateTimeImmutable;

class LogMessage implements LogMessageInterface
{
    private $timestamp;

    private $message;

    private $stackTrace = '';

    private $errorType;

    public function __construct(string $logLine)
    {
        $json = json_decode($logLine, true);
        $this
            ->setTimestamp($json['@timestamp'])
            ->setMessage($json['@message'])
            ->setErrorType($json['@message']);
        if (isset($json['@fields']['ctxt_trace'])) {
            $this->setStackTrace($json['@fields']['ctxt_trace']);
        }

    }

    private function setTimestamp(?string $dateTime = null): self
    {
        $this->timestamp = new DateTimeImmutable($dateTime);
        return $this;
    }

    public function getTimestamp(): ?DateTimeImmutable
    {
        return $this->timestamp;
    }

    private function setMessage(?string $message = null): self
    {
        $this->message = $message == null ? '' : $message;

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

    private function setErrorType($message): self
    {
        if (strpos('Error-Code: ', $message) === 0) {
            $this->errorType = substr($message, 0, 44);
        } else {
            $this->errorType = substr($message, 0, strpos($message, ' - '));
        }

        return $this;
    }

    public function getErrorType(): string
    {
        return $this->errorType;
    }
}
