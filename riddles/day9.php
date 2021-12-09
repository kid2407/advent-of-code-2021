<?php
/**
 * @author Tobias Franz
 */

include_once '../InputHelper.php';

const INPUT_FILE = '../inputs/day9.txt';

/**
 * @param array $lines
 * @return array
 */
function createHeightmapFromInput(array $lines): array
{
    $heightmap = [];

    foreach ($lines as $row => $line) {
        foreach (str_split($line) as $column => $point) {
            $heightmap[$row][$column] = (int)$point;
        }
    }

    return $heightmap;
}

/**
 * @param array $heightmap
 * @return array
 */
function getLowPointsFromHeightmap(array $heightmap): array
{
    $lowPoints = [];

    foreach ($heightmap as $row => $rowContent) {
        foreach ($rowContent as $column => $point) {
            $top        = $heightmap[$row - 1][$column] ?? PHP_INT_MAX;
            $left       = $rowContent[$column - 1] ?? PHP_INT_MAX;
            $bottom     = $heightmap[$row + 1][$column] ?? PHP_INT_MAX;
            $right      = $rowContent[$column + 1] ?? PHP_INT_MAX;
            $isLowPoint = ($top > $point && $left > $point && $bottom > $point && $right > $point);
            if ($isLowPoint) {
                $lowPoints[] = ['row' => $row, 'column' => $column, 'value' => $point];
            }
        }
    }

    return $lowPoints;
}

$lines = InputHelper::readFileAsStrings(INPUT_FILE);

// Task 1
$start     = microtime(true);
$heightmap = createHeightmapFromInput($lines);
$lowPoints = getLowPointsFromHeightmap($heightmap);
$end       = microtime(true);
echo sprintf('Found %d points with a combined risk value of %d in %.3fms%s', count($lowPoints), array_sum(array_map(function ($lowPoint) {
    return $lowPoint['value'] + 1;
}, $lowPoints)), ($end - $start) * 1000, PHP_EOL);