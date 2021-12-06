<?php
/**
 * @author Tobias Franz
 */

include_once '../InputHelper.php';

const INPUT_FILE = '../inputs/day6';

$inputData   = InputHelper::readFileAsString(INPUT_FILE);
$initialAges = explode(',', $inputData);

/**
 * @param array $lanternFish
 * @return array
 */
function updateInternalTimers(array $lanternFish): array
{
    $newFishCount = count(array_filter($lanternFish, function ($fish) {
        return $fish === 0;
    }));

    $lanternFish = array_map(function ($fish) {
        $createdFish = $fish === 0;
        return $createdFish ? 6 : $fish - 1;
    }, $lanternFish);

    if ($newFishCount > 0) {
        for ($i = 1; $i <= $newFishCount; $i++) {
            $lanternFish[] = 8;
        }
    }

    return $lanternFish;
}

/**
 * @param array $lanternFish
 * @param int $days
 */
function ageFish(array $lanternFish, int $days)
{
    $start = microtime(true);
    for ($day = 0; $day < $days; $day++) {

        if ($day % floor($days / 20) === 0) {
            $end = microtime(true);
            echo sprintf('Day %d of %d (%fs)%s', $day, $days, $end - $start, PHP_EOL);
            $start = microtime(true);
        }
        $lanternFish = updateInternalTimers($lanternFish);
    }
    $end = microtime(true);

    echo sprintf('Number of total fish after %d days: %d (%f)s%s', $days, count($lanternFish), $end - $start, PHP_EOL);
}

// Task 1
ageFish($initialAges, 80);

// Task 2
ageFish($initialAges, 256);