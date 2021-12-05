<?php
/**
 * @author Tobias Franz
 */

include_once '../InputHelper.php';

const INPUT_FILE = '../inputs/day5';

$inputData = InputHelper::readFileAsStrings(INPUT_FILE);

$lines = array_map(function ($value) {
    return explode(' -> ', $value);
}, $inputData);

$grid = [];

function getPointsOnLine(array $coordinates, bool $includeDiagonal = false): array
{
    $coordinates = array_map(function ($value) {
        return explode(',', $value);
    }, $coordinates);

    $x1 = intval($coordinates[0][0]);
    $y1 = intval($coordinates[0][1]);
    $x2 = intval($coordinates[1][0]);
    $y2 = intval($coordinates[1][1]);

    $points = [];

    // horizontal
    if ($x1 === $x2) {
        $start = min($y1, $y2);
        $end   = max($y1, $y2);
        foreach (range($start, $end) as $singlePoint) {
            $points[] = [$x1, $singlePoint];
        }
    }

    // vertical
    if ($y1 === $y2) {
        $start = min($x1, $x2);
        $end   = max($x1, $x2);
        foreach (range($start, $end) as $singlePoint) {
            $points[] = [$y1, $singlePoint];
        }
    }

    return $points;
}

/**
 * @param array $grid
 * @param array $points
 * @return array
 */
function addPointsToGrid(array $grid, array $points): array
{
    foreach ($points as $point) {
        $key = sprintf('%d_%d', $point[0], $point[1]);
        if (!isset($grid[$key])) {
            $grid[$key] = 1;
        } else {
            $grid[$key]++;
        }
    }

    return $grid;
}

$lineCount = 0;
foreach ($lines as $line) {
    $points = getPointsOnLine($line);
    if (!empty($points)) {
        $lineCount++;
//        echo sprintf("Line number %s: ", $lineCount);
//        print_r($points);

        $grid = addPointsToGrid($grid, $points);
    }
}

echo "Current grid data:" . PHP_EOL;
print_r($grid);

$intersectionCount = count(array_filter($grid, function ($value) {
    return $value > 1;
}));

echo "Number of intersections of 2 or more lines: " . $intersectionCount;
