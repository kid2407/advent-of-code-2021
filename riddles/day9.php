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
    $lowPointRiskLevel = 0;
    $lowestPoints      = [];

    foreach ($heightmap as $row => $rowContent) {
        foreach ($rowContent as $column => $point) {
            $top        = $heightmap[$row - 1][$column] ?? PHP_INT_MAX;
            $left       = $rowContent[$column - 1] ?? PHP_INT_MAX;
            $bottom     = $heightmap[$row + 1][$column] ?? PHP_INT_MAX;
            $right      = $rowContent[$column + 1] ?? PHP_INT_MAX;
            $isLowPoint = ($top > $point && $left > $point && $bottom > $point && $right > $point);
            if ($isLowPoint) {
                $lowPointRiskLevel += $point + 1;
                $lowestPoints[]    = [
                    'row'    => $row,
                    'column' => $column,
                    'value'  => $point,
                ];
            }
        }
    }

    return [$lowestPoints, $lowPointRiskLevel];
}

/**
 * @param array $heightmap
 * @param array $lowPoints
 * @param int $limit
 * @return int[]
 */
function getLargestBasinsFromHeightmap(array $heightmap, array $lowPoints, int $limit): array
{
    $basins = [];

    foreach ($lowPoints as $key => $lowPoint) {
        $currentBasin = [$lowPoint];
        $foundPoints  = [];
        while (!empty($currentBasin)) {
            foreach ($currentBasin as $key => $undeterminedPoint) {
                $topValue    = $heightmap[$undeterminedPoint['row'] - 1][$undeterminedPoint['column']] ?? PHP_INT_MAX;
                $leftValue   = $heightmap[$undeterminedPoint['row']][$undeterminedPoint['column'] - 1] ?? PHP_INT_MAX;
                $bottomValue = $heightmap[$undeterminedPoint['row'] + 1][$undeterminedPoint['column']] ?? PHP_INT_MAX;
                $rightValue  = $heightmap[$undeterminedPoint['row']][$undeterminedPoint['column'] + 1] ?? PHP_INT_MAX;

                $newBasin = [];

                if ($topValue < 9) {
                    $row    = $undeterminedPoint['row'] - 1;
                    $column = $undeterminedPoint['column'];
                    $key    = $row . '_' . $column;
                    if (!isset($foundPoints[$key]) && isset($heightmap[$row][$column])) {
                        $newBasinEntry     = ['row' => $row, 'column' => $column, 'point' => $heightmap[$row][$column]];
                        $newBasin[]        = $newBasinEntry;
                        $foundPoints[$key] = true;
                    }
                }
                if ($leftValue < 9) {
                    $row    = $undeterminedPoint['row'];
                    $column = $undeterminedPoint['column'] - 1;
                    $key    = $row . '_' . $column;
                    if (!isset($foundPoints[$key]) && isset($heightmap[$row][$column])) {
                        $newBasinEntry     = ['row' => $row, 'column' => $column, 'point' => $heightmap[$row][$column]];
                        $newBasin[]        = $newBasinEntry;
                        $foundPoints[$key] = true;
                    }
                }
                if ($bottomValue < 9) {
                    $row    = $undeterminedPoint['row'] + 1;
                    $column = $undeterminedPoint['column'];
                    $key    = $row . '_' . $column;
                    if (!isset($foundPoints[$key]) && isset($heightmap[$row][$column])) {
                        $newBasinEntry     = ['row' => $row, 'column' => $column, 'point' => $heightmap[$row][$column]];
                        $newBasin[]        = $newBasinEntry;
                        $foundPoints[$key] = true;
                    }
                }
                if ($rightValue < 9) {
                    $row    = $undeterminedPoint['row'];
                    $column = $undeterminedPoint['column'] + 1;
                    $key    = $row . '_' . $column;
                    if (!isset($foundPoints[$key]) && isset($heightmap[$row][$column])) {
                        $newBasinEntry     = ['row' => $row, 'column' => $column, 'point' => $heightmap[$row][$column]];
                        $newBasin[]        = $newBasinEntry;
                        $foundPoints[$key] = true;
                    }
                }
                $foundPoints[$undeterminedPoint['row'] . '_' . $undeterminedPoint['column']] = true;

                $currentBasin = $newBasin;
            }
        }
        $basins[] = count($foundPoints);
    }

    rsort($basins);

    return $basins;
}

$lines = InputHelper::readFileAsStrings(INPUT_FILE);

// Task 1
$start     = microtime(true);
$heightmap = createHeightmapFromInput($lines);
$end       = microtime(true);
echo sprintf('Created heightmap in %.3fms%s', ($end - $start) * 1000, PHP_EOL);
$start = microtime(true);
[$lowPoints, $lowPointsRiskLevel] = getLowPointsFromHeightmap($heightmap);
$end = microtime(true);
echo sprintf('Found points with a combined risk value of %d in %.3fms%s', $lowPointsRiskLevel, ($end - $start) * 1000, PHP_EOL);

// Task 2
$start      = microtime(true);
$basinCount = 3;
$basins     = getLargestBasinsFromHeightmap($heightmap, $lowPoints, $basinCount);
$end        = microtime(true);
echo sprintf('Found the %d largest basins with a combined size of %d in %.3fms%s', $basinCount, array_sum($basins), ($end - $start) * 1000, PHP_EOL);