<?php
/**
 * @author Tobias Franz
 */

const INPUT_FILE = '../inputs/day1.txt';

if (!file_exists(INPUT_FILE)) {
    echo "No input file found!" . PHP_EOL;
    die();
}


$fileHandle = fopen(INPUT_FILE, 'r');
if (!$fileHandle) {
    echo "Could not open file!" . PHP_EOL;
    die();
}

$previousMeasurement = null;
$timesIncreased      = 0;

while (($line = fgets($fileHandle)) !== false) {
    $line = trim($line);
    if (is_null($previousMeasurement)) {
        $previousMeasurement = $line;
        continue;
    }
    if ($line > $previousMeasurement) {
        $timesIncreased++;
    }
    $previousMeasurement = $line;
}

fclose($fileHandle);
echo sprintf("The measurement was deeper than before %d times.%s", $timesIncreased, PHP_EOL);
echo "Finished running!" . PHP_EOL;