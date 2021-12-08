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

$lastTripleMeasurement    = null;
$currentTripleMeasurement = null;
$timesIncreased           = 0;
$values                   = [];

while (($line = fgets($fileHandle)) !== false) {
    $values[] = intval($line);
}

fclose($fileHandle);

$maxIterationCount = count($values) - 2;

for ($i = 0; $i < $maxIterationCount; $i++) {
    $currentTripleMeasurement = $values[$i] + $values[$i + 1] + $values[$i + 2];
    if (!is_null($lastTripleMeasurement)) {
        if ($currentTripleMeasurement > $lastTripleMeasurement) {
            $timesIncreased++;
        }
    }

    $lastTripleMeasurement = $currentTripleMeasurement;
}

echo sprintf("The measurement was deeper than before %d times.%s", $timesIncreased, PHP_EOL);
echo "Finished running!" . PHP_EOL;