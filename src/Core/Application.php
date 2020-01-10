<?php

namespace Elrond\Core;

use Elrond\LogFile\LogFile;
use Elrond\LogFile\LogMessage;
use Elrond\Terminal\Terminal;

class Application
{
    private const NUMBER_OF_LINES_TO_READ = 20;
    private const TIMESTAMP_FORMAT = 'Y-m-d H:i:s';

    private const PROMPT_CALL_TO_ACTION = '[Q]uit, [R]efresh, <Line Number>:';
    private const PROMPT_COMMAND_NOT_FOUND = 'Unrecognized Command';
    private const PROMPT_HELP = 'Usage: logreader <filename>';

    private const COMMAND_REFRESH = 'r';
    private const COMMAND_QUIT = 'q';

    public static function execute(array $arguments, int $numberOfArguments)
    {
        $t = new Terminal();
        if (!isset($arguments[1]) || $numberOfArguments <> 2) {
            static::showHelpMessage($t);
            exit(0);
        }
        $filename = $arguments[1];

        if (!file_exists($filename)) {
            die($filename . ' not found');
        }

        $logFile = new LogFile($filename);

        $lines = [];
        $input = static::COMMAND_REFRESH;
        while ($input !== static::COMMAND_QUIT) {
            if ($input == static::COMMAND_REFRESH) {
                $lines = $logFile->getLastXLines(static::NUMBER_OF_LINES_TO_READ);
                static::showLastMessages($t, $lines);
            } else if (is_int((int)$input) && $input > 0 && $input <= count($lines)) {
                static::showDetailedMessage($t, $lines[count($lines) - $input]);
            } else {
                static::showCommandNotFoundNotification($t);
            }

            self::showPrompt($t);
            $input = mb_strtolower(readline());
        }
    }

    public static function showLastMessages(Terminal $t, array $lines)
    {
        $numberOfLines = count($lines);
        $nolWidth = mb_strlen((string)$numberOfLines);

        $formatLineNumber = function ($number) use ($nolWidth) {
            return str_repeat(' ', $nolWidth - mb_strlen($number)) . $number;
        };

        foreach ($lines as $line) {
            $logMessage = new LogMessage($line);

            $output = $formatLineNumber($numberOfLines--) . ': ' . $logMessage->getTimestamp()->format(static::TIMESTAMP_FORMAT) . ' > ' . $logMessage->getMessage();
            if (mb_strlen($output) > $t->getNumberOfCols()) {
                $output = mb_substr($output, 0, $t->getNumberOfCols() - 4) . '...';
            }

            $t->pl($output);
        }
    }

    public static function showPrompt(Terminal $t)
    {
        $t->pl(str_repeat('_', mb_strlen(static::PROMPT_CALL_TO_ACTION)));
        $t->pl(static::PROMPT_CALL_TO_ACTION);
    }

    public static function showDetailedMessage(Terminal $t, string $line)
    {
        $logMessage = new LogMessage($line);
        $t->pl($logMessage->getTimestamp()->format(static::TIMESTAMP_FORMAT));
        $t->pl($logMessage->getErrorType());
        $msg = substr($logMessage->getMessage(), mb_strlen($logMessage->getErrorType()) + (in_array($logMessage->getErrorType(), ['Error-Code', '']) ? 0 : 3));

        $arrMessage = explode("\\n", $msg);
        foreach ($arrMessage as $msgLine) {
            $t->pl($msgLine);
        }
        if ($logMessage->hasStackTrace()) {
            $arrStackTrace = explode("\\n", $logMessage->getStackTrace());

            foreach ($arrStackTrace as $traceLine) {
                $t->pl($traceLine);
            }
        }
    }

    public static function showCommandNotFoundNotification(Terminal $t)
    {
        $t->pl(self::PROMPT_COMMAND_NOT_FOUND);
    }

    public static function showHelpMessage(Terminal $t)
    {
        $t->pl(static::PROMPT_HELP);
    }
}
