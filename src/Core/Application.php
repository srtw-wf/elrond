<?php

namespace Elrond\Core;

use Elrond\LogFile\LogFile;
use Elrond\LogFile\LogMessage;
use Elrond\Terminal\Terminal;

class Application
{
    const NUMBER_OF_LINES_TO_READ = 20;
    const TIMESTAMP_FORMAT = 'Y-m-d H:i:s';

    public static function execute(array $arguments)
    {
        $filename = $arguments[1];

        if (!file_exists($filename)) {
            die($filename . ' not found');
        }

        $logFile = new LogFile($filename);
        $lines = $logFile->getLastXLines(static::NUMBER_OF_LINES_TO_READ);

        $t = new Terminal();


        static::showLastMessages($t, $lines);

        $input = '';
        while ($input !== 'q') {
            if ($input == 'r') {
                $lines = $logFile->getLastXLines(static::NUMBER_OF_LINES_TO_READ);
                static::showLastMessages($t, $lines);
            }

            if (is_int((int)$input) && $input > 0 && $input < count($lines)) {
                static::showDetailedMessage($t, $lines[count($lines) - $input]);
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
        $t->pl('[Q]uit, [R]efresh: ');
    }

    public static function showDetailedMessage(Terminal $t, string $line)
    {
        $logMessage = new LogMessage($line);
        $t->pl($logMessage->getTimestamp()->format(static::TIMESTAMP_FORMAT));
        $arrMessage = explode('\n', $logMessage->getMessage());
        foreach ($arrMessage as $msgLine) {
            $t->pl($msgLine);
        }
    }
}
