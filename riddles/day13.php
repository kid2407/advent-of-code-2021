<?php
/**
 * @author Tobias Franz
 */

include_once '../InputHelper.php';

const INPUT_FILE = '../inputs/day13.txt';

/**
 * @param string[] $lines
 * @return array
 */
function parseInputLines(array $lines): array
{
    $points           = [];
    $foldInstructions = [];
    $pointsFinished   = false;

    foreach ($lines as $line) {
        if ($pointsFinished) {
            $instructionData    = explode('=', substr($line, 11));
            $foldInstructions[] = [
                'direction' => $instructionData[0],
                'position'  => intval($instructionData[1]),
            ];
        } else {
            if (empty($line)) {
                $pointsFinished = true;
                continue;
            }
            $coords                         = explode(',', $line);
            $points[$coords[1]][$coords[0]] = true;
        }
    }

    return [$points, $foldInstructions];
}

/**
 * @param array $points
 * @param array $instruction
 * @return array
 */
function fold(array $points, array $instruction): array
{
    $transformed = [];

    $direction = $instruction['direction'];
    $position  = $instruction['position'];

    foreach ($points as $row => $line) {
        foreach ($line as $column => $point) {
            if ($direction === 'y') {
                if ($row > $position) {
                    $transformed[$position - ($row - $position)][$column] = $point;
                } else {
                    $transformed[$row][$column] = $point;
                }
            } elseif ($direction === 'x') {
                if ($column > $position) {
                    $transformed[$row][$position - ($column - $position)] = $point;
                } else {
                    $transformed[$row][$column] = $point;
                }
            }
        }

    }


    return $transformed;
}

function countVisibleDots(array $points): int
{
    $count = 0;
    foreach ($points as $line) {
        $count += count($line);
    }

    return $count;
}

function displayPointMap(array $points): void
{
    $maxX = 0;
    $maxY = 0;

    foreach ($points as $lineNumber => $line) {
        $maxX = $lineNumber > $maxX ? $lineNumber : $maxX;
        foreach ($line as $columnNumber => $column) {
            $maxY = $columnNumber > $maxY ? $columnNumber : $maxY;
        }
    }

    for ($x = 0; $x <= $maxX; $x++) {
        echo PHP_EOL;
        for ($y = 0; $y <= $maxY; $y++) {
            echo isset($points[$x][$y]) ? '#' : '.';
        }
    }
    echo PHP_EOL;
}

$lines = InputHelper::readFileAsStrings(INPUT_FILE);

[$points, $foldInstructions] = parseInputLines($lines);

$instruction = $foldInstructions[0];

// Task 1
$start        = microtime(true);
$foldedPoints = fold($points, $instruction);
$end          = microtime(true);
echo sprintf("After folding once with direction=%s and position=%d (%s visible dots) in %.3fms%s", $instruction['direction'], $instruction['position'], countVisibleDots($foldedPoints), ($end - $start) * 1000, PHP_EOL);

// Task 1
$start        = microtime(true);
$foldedPoints = $points;
foreach ($foldInstructions as $instruction) {
    $foldedPoints = fold($foldedPoints, $instruction);
}
$end = microtime(true);
echo sprintf("After folding %s times (%s visible dots) in %.3fms%s", count($foldInstructions), countVisibleDots($foldedPoints), ($end - $start) * 1000, PHP_EOL);
displayPointMap($foldedPoints);
