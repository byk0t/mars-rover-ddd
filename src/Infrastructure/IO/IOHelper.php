<?php


namespace Infrastructure\IO;


class IOHelper
{
    public static function safeReadLine($handle)
    {
        $line = fgets($handle);
        if($line !== false) {
            $line = trim($line);
        }
        return $line;
    }
}