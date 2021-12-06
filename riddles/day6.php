<?php
/**
 * @author Tobias Franz
 */

include_once '../InputHelper.php';

const INPUT_FILE = '../inputs/day6';

$inputData        = InputHelper::readFileAsString(INPUT_FILE);
$initialAges      = explode(',', $inputData);
$fishGroupedByAge = [];
foreach ($initialAges as $age) {
    if (!isset($fishGroupedByAge[$age])) {
        $fishGroupedByAge[$age] = 0;
    }
    $fishGroupedByAge[$age]++;
}

/**
 * @param array $lanternFish
 * @return array
 */
function updateInternalTimers(array $lanternFish): array
{
    $newGeneration = [];
    foreach ($lanternFish as $fishAge => $fish) {
        $newGeneration[$fishAge - 1] = $fish;
    }

    if (isset($newGeneration[-1])) {
        $newGeneration[8] = $newGeneration[-1];
        $newGeneration[6] = $newGeneration[-1] + ($newGeneration[6] ?? 0);
        unset($newGeneration[-1]);
    }

    return $newGeneration;
}

/**
 * @param array $lanternFish
 * @param int $days
 */
function ageFish(array $lanternFish, int $days)
{
    $start = microtime(true);
    for ($day = 0; $day < $days; $day++) {
        $lanternFish = updateInternalTimers($lanternFish);
    }
    $end = microtime(true);

    echo sprintf('Number of total fish after %d days: %d (%f)s%s', $days, array_sum($lanternFish), $end - $start, PHP_EOL);
}

// Task 1
ageFish($fishGroupedByAge, 80);

// Task 2
ageFish($fishGroupedByAge, 256);