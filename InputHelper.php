<?php

/**
 * @author Tobias Franz
 */
class InputHelper
{
    private static function readFileAsLines(string $filename): array
    {
        if (!file_exists($filename)) {
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
        }, self::readFileAsLines($filename));
    }

    /**
     * @param string $filename
     * @return string
     */
    public static function readFileAsString(string $filename): string
    {
        if (!file_exists($filename)) {
            throw new RuntimeException("No input file found.");
        }

        $content = file_get_contents($filename);
        if (!$content) {
            throw new RuntimeException("Could not open input file.");
        }

        return trim($content);
    }

    /**
     * @param string $filename
     * @return int[]
     */
    public static function readFileAsInts(string $filename): array
    {
        return array_map(function ($string) {
            return intval($string);
        }, self::readFileAsLines($filename));
    }
}