<?php

/**
 * @author Tobias Franz
 */
class InputHelper
{
    private static function readFile(string $filename): array
    {
        if (!file_exists(INPUT_FILE)) {
            throw new RuntimeException("No input file found.");
        }

        $handle = fopen($filename, 'r');
        if (!$handle) {
            throw new RuntimeException("Could not open input file.");
        }

        $values = [];

        while (($line = fgets($handle)) !== false) {
            $values[] = $line;
        }

        fclose($handle);

        return $values;
    }

    /**
     * @param string $filename
     * @return string[]
     */
    public static function readFileAsStrings(string $filename): array
    {
        return array_map(function ($string) {
            return trim($string);
        }, self::readFile($filename));
    }

    /**
     * @param string $filename
     * @return int[]
     */
    public static function readFileAsInts(string $filename): array
    {
        return array_map(function ($string) {
            return intval($string);
        }, self::readFile($filename));
    }
}