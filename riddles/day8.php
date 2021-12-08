<?php
/**
 * @author Tobias Franz
 */

include_once '../InputHelper.php';

const INPUT_FILE = '../inputs/day8.txt';

/**
 * @param array $lines
 * @return array
 */
function parseData(array $lines): array
{
    $parsedData = [];

    foreach ($lines as $line) {
        $split           = explode(' | ', $line);
        $patterns        = explode(' ', $split[0]);
        $displayedDigits = explode(' ', $split[1]);

        $parsedData[] = ['signals' => $patterns, 'digits' => $displayedDigits];
    }

    return $parsedData;
}

/**
 * @param array $entry
 * @return int
 */
function countSimpleDigits(array $entry): int
{
    $count = 0;

    foreach ($entry['digits'] as $digit) {
        $digitLenght = strlen($digit);
        $count       += ($digitLenght === 2 || $digitLenght === 3 || $digitLenght === 4 || $digitLenght === 5) ? 1 : 0;
    }

    return $count;
}

/**
 * @param array $signals
 * @return array
 */
function determineDigitMap(array $signals): array
{
    $digitMap = [];
    foreach ($signals as $key => $signal) {
        $length = strlen($signal);
        if ($length === 2) {
            $digitMap[1] = $signal;
            unset($signals[$key]);
        } elseif ($length === 3) {
            $digitMap[7] = $signal;
            unset($signals[$key]);
        } elseif ($length === 4) {
            $digitMap[4] = $signal;
            unset($signals[$key]);
        } elseif ($length === 7) {
            $digitMap[8] = $signal;
            unset($signals[$key]);
        }
    }

    // Determine digit for a (top horizontal)
    $signalsForOne = str_split($digitMap[1]);

    // Determine 6 (length 6)
    $sixLongSignals = array_filter($signals, function ($value) {
        return strlen($value) === 6;
    });

    $signals = array_diff($signals, $sixLongSignals);

    foreach ($sixLongSignals as $key => $sixLongSignal) {
        if (!empty(array_diff($signalsForOne, str_split($sixLongSignal)))) {
            $digitMap[6] = $sixLongSignal;
            unset($sixLongSignals[$key]);
        }
    }

    // Determine which of the remaining 6-digit signals is wich one, by comparing them to the strokes of 4 - if they completely overlap, it is a 9
    $sixLongKeys    = array_keys($sixLongSignals);
    $digitFourSplit = str_split($digitMap[4]);
    if (empty(array_diff($digitFourSplit, str_split($sixLongSignals[$sixLongKeys[0]])))) {
        $digitMap[9] = $sixLongSignals[$sixLongKeys[0]];
        $digitMap[0] = $sixLongSignals[$sixLongKeys[1]];
    } else {
        $digitMap[9] = $sixLongSignals[$sixLongKeys[1]];
        $digitMap[0] = $sixLongSignals[$sixLongKeys[0]];
    }

    $fiveLongSignals = array_filter($signals, function ($value) {
        return strlen($value) === 5;
    });

    // If one of these overlaps with the one completely, it has to be 3
    $fiveLongKeys = array_keys($fiveLongSignals);
    foreach ($fiveLongSignals as $key => $fiveLongSignal) {
        if (empty(array_diff($signalsForOne, str_split($fiveLongSignal)))) {
            $digitMap[3] = $fiveLongSignal;
            unset ($signals[array_search($fiveLongSignal, $signals)]);
            unset($fiveLongSignals[$key]);
        }
    }

    // only 2 and 5 remaining
    $firstValue  = array_shift($signals);
    $secondValue = array_shift($signals);

    if (count(array_diff(str_split($digitMap[9]), str_split($firstValue))) === 3) {
        // Es ist 2
        $digitMap[2] = $firstValue;
        $digitMap[5] = $secondValue;
    } else {
        // Es ist 5
        $digitMap[2] = $secondValue;
        $digitMap[5] = $firstValue;
    }

    /** split_first <diff> split_9: Wenn anzahl der überlappung == 3, dann ist es eine 2 (Überlappung oben, oben-rechts und mitte) */
    /**
     * xxxxx     xxxxx     xxxxx
     * x   x         x     x
     * xxxxx     xxxxx     xxxxx
     *     x     x             x
     * xxxxx     xxxxx     xxxxx
     */

    return $digitMap;
}

/**
 * @param array $digitMap
 * @return array
 */
function determinePattern(array $digitMap): array
{
    $pattern  = [];
    $digitMap = array_map('str_split', $digitMap);

    // Top line
    $diffSevenOne = array_diff($digitMap[7], $digitMap[1]);
    $pattern['a'] = array_values($diffSevenOne)[0];

    // Right side


    return $pattern;
}

/**
 * @param array $map
 * @param array $digits
 * @return int
 */
function getOutputValueFromMap(array $map, array $digits): int
{
    $finalNumber = 0;
    $digitLength = count($digits);
    $map         = array_map(function ($value) {
        $chars = str_split($value);
        sort($chars);
        return implode('', $chars);
    }, $map);
    $map         = array_flip($map);

    for ($i = 0; $i < $digitLength; $i++) {
        $digitCharacters = str_split($digits[$i]);
        sort($digitCharacters);
        $finalNumber += $map[implode('', $digitCharacters)] * pow(10, $digitLength - $i - 1);
    }

    return $finalNumber;
}

$inputData  = InputHelper::readFileAsStrings(INPUT_FILE);
$parsedData = parseData($inputData);

//Task 1
$start = microtime(true);
$total = 0;
foreach ($parsedData as $key => $entry) {
    $count = countSimpleDigits($entry);
    $total += $count;
}

$end = microtime(true);
echo sprintf('Total count is %d calculated in %.3fms%s', $total, ($end - $start) * 1000, PHP_EOL);

//Task 2
$start = microtime(true);
$total = 0;
foreach ($parsedData as $key => $entry) {
    $digitMap   = determineDigitMap($entry['signals']);
    $patternMap = determinePattern($digitMap);
    $count      = getOutputValueFromMap($digitMap, $entry['digits']);
    echo sprintf('Output for Entry %d: %d%s', $key, $count, PHP_EOL);
    $total += $count;
}

$end = microtime(true);
echo sprintf('Total sum is %d calculated in %.3fms%s', $total, ($end - $start) * 1000, PHP_EOL);