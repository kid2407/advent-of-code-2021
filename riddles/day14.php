<?php
/**
 * @author Tobias Franz
 */

include_once '../InputHelper.php';

const INPUT_FILE = '../inputs/day14.txt';

/**
 * @param string[] $lines
 * @return array
 */
function parseInputLines(array $lines): array
{
    $polymerTemplate = '';
    $pairInsertions  = [];

    foreach ($lines as $index => $line) {
        if ($index === 0) {
            $polymerTemplate = $line;
        } else {
            if (!empty($line)) {
                $insertionData                     = explode(' -> ', $line);
                $pairInsertions[$insertionData[0]] = $insertionData[1];
            }
        }
    }

    return [$polymerTemplate, $pairInsertions];
}

/**
 * @param string $polymerTemplate
 * @param array $insertionRules
 * @return string
 */
function performSingleInsertion(string $polymerTemplate, array $insertionRules): string
{
    $newPolymerTemplate = '';

    for ($l = 0; $l < strlen($polymerTemplate); $l++) {
        if (isset($polymerTemplate[$l + 1])) {
            $match = substr($polymerTemplate, $l, 2);
            if (isset($insertionRules[$match])) {
                $newPolymerTemplate .= $polymerTemplate[$l] . $insertionRules[$match];
            }
        } else {
            $newPolymerTemplate .= $polymerTemplate[$l];
        }
    }

    return $newPolymerTemplate;
}

/**
 * @param string $polymerTemplate
 * @param array $insertionRules
 * @param int $steps
 * @return string
 */
function performInsertions(string $polymerTemplate, array $insertionRules, int $steps): string
{
    for ($i = 1; $i <= $steps; $i++) {
        echo sprintf("Starting step %d%s", $i, PHP_EOL);
        $start           = microtime(true);
        $polymerTemplate = performSingleInsertion($polymerTemplate, $insertionRules);
        echo sprintf("Step %d took %.4fs%s", $i, microtime(true) - $start, PHP_EOL);
    }

    return $polymerTemplate;
}

/**
 * @param string $polymerTemplate
 * @param array $insertionRules
 * @param int $steps
 * @return array
 */
function performInsertionsEfficient(string $polymerTemplate, array $insertionRules, int $steps): array
{
    $pairs = [];
    foreach ($insertionRules as $pair => $insertion) {
        $pairs[$pair] = substr_count($polymerTemplate, $pair);
    }

    foreach (range(1, $steps) as $ignored) {
        foreach ($pairs as $pair => $insertion) {
            $count = count($insertion);
            if ($count > 0) {
                $firstPair  = $pair[0] . $insertion;
                $secondPair = $insertion . $pair[1];
                if (!isset($pairs[$firstPair])) {
                    $pairs[$firstPair] = $count;
                } else {
                    $pairs[$firstPair] += $count;
                }

                if (!isset($pairs[$secondPair])) {
                    $pairs[$secondPair] = $count;
                } else {
                    $pairs[$secondPair] += $count;
                }
            }
        }
    }

    return $pairs;
}

$lines = InputHelper::readFileAsStrings(INPUT_FILE);
[$polymerTemplate, $insertionRules] = parseInputLines($lines);

// Task 1
$start           = microtime(true);
$steps           = 10;
$newPairs        = performInsertionsEfficient($polymerTemplate, $insertionRules, $steps);
$characterCounts = [];
foreach ($newPairs as $pair => $amount) {
    $firstChar  = $pair[0];
    $secondChar = $pair[1];
    if (!isset($characterCounts[$firstChar])) {
        $characterCounts[$firstChar] = $amount;
    } else {
        $characterCounts[$firstChar] += $amount;
    }
    if (!isset($characterCounts[$secondChar])) {
        $characterCounts[$secondChar] = $amount;
    } else {
        $characterCounts[$secondChar] += $amount;
    }
}

$polymerTemplate          = performInsertions($polymerTemplate, $insertionRules, $steps);
$splitPolymerStringCounts = array_count_values(str_split($polymerTemplate));
$maxCount                 = max($splitPolymerStringCounts);
$minCount                 = min($splitPolymerStringCounts);
$end                      = microtime(true);
echo sprintf("New polymer template has length %s after %d steps in %.3fms%sThe result is %d - %d = %d%s", strlen($polymerTemplate), $steps, ($end - $start) * 1000, PHP_EOL, $maxCount, $minCount, $maxCount - $minCount, PHP_EOL);

// Task 2
//$start                    = microtime(true);
//$steps                    = 40;
//$polymerTemplate          = performInsertions($polymerTemplate, $insertionRules, $steps);
//$splitPolymerStringCounts = array_count_values(str_split($polymerTemplate));
//$maxCount                 = max($splitPolymerStringCounts);
//$minCount                 = min($splitPolymerStringCounts);
//$end                      = microtime(true);
//echo sprintf("New polymer template has length %s after %d steps in %.3fms%sThe result is %d - %d = %d%s", strlen($polymerTemplate), $steps, ($end - $start) * 1000, PHP_EOL, $maxCount, $minCount, $maxCount - $minCount, PHP_EOL);