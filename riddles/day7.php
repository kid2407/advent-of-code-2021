<?php
/**
 * @author Tobias Franz
 */

include_once '../InputHelper.php';

const INPUT_FILE = '../inputs/day7';

$inputData            = InputHelper::readFileAsString(INPUT_FILE);
$initialCrabPositions = array_map('intval', explode(',', $inputData));

/**
 * @param array $positions
 * @param int $targetPosition
 * @param bool $useCrabEngineering
 * @return int
 */
function determineFuelUsageToPosition(array $positions, int $targetPosition, bool $useCrabEngineering): int
{
    $fuelUsage = 0;

    foreach ($positions as $position) {
        $diff      = $targetPosition > $position ? $targetPosition - $position : $position - $targetPosition;
        $fuelUsage += $useCrabEngineering ? ($diff * $diff + $diff) / 2 : $diff;
    }

    return $fuelUsage;
}

/**
 * @param array $positions
 * @param bool $useCrabEngineering
 * @return int
 */
function getLowestPossibleFuelUsage(array $positions, bool $useCrabEngineering = false): int
{
    $minimumFuelUsage = PHP_INT_MAX;
    $unique           = [];
    foreach ($positions as $position) {
        if (!in_array($position, $positions)) {
            $unique[] = $position;
        }
    }

    foreach ($unique as $position) {
        $fuelUsage        = determineFuelUsageToPosition($positions, $position, $useCrabEngineering);
        $minimumFuelUsage = min($minimumFuelUsage, $fuelUsage);
    }

    return $minimumFuelUsage;
}

// Task 1
$start            = microtime(true);
$minimumFuelUsage = getLowestPossibleFuelUsage($initialCrabPositions);
$end              = microtime(true);
echo sprintf('Lowest possible fuel usage is %d found in %.3fms%s', $minimumFuelUsage, ($end - $start) * 1000, PHP_EOL);

// Task 2
$start            = microtime(true);
$minimumFuelUsage = getLowestPossibleFuelUsage($initialCrabPositions, true);
$end              = microtime(true);
echo sprintf('Lowest possible fuel usage with crab engineering is %d found in %.3fms%s', $minimumFuelUsage, ($end - $start) * 1000, PHP_EOL);