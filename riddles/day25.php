<?php
/**
 * @author Tobias Franz
 */

include_once '../InputHelper.php';

const INPUT_FILE = '../inputs/day25.txt';

const CUCUMBER_EAST = '>';
const CUCUMBER_SOUTH = 'v';
const CUCUMBER_NONE = '.';

const DIRECTION_EAST = 'east';
const DIRECTION_SOUTH = 'south';

/**
 * @param string[] $input
 * @return array
 */
function transformInput(array $input): array
{
    $out = [];

    foreach ($input as $row) {
        $out[] = str_split($row);
    }

    return $out;
}

/**
 * @param string[][] $field
 */
function displayField(array $field)
{
    $width = count($field[0]);

    echo "\n" . str_repeat('--', $width - 1) . "-\n";

    foreach ($field as $row) {
        echo join(' ', $row) . "\n";
    }

    echo str_repeat('--', $width - 1) . "-\n";
}


/**
 * @param string[][] $field
 * @param string $direction
 * @return array
 */
function move(array $field, string $direction): array
{
    $hasMoved = false;
    $maxColumnIndex = count($field[0]) - 1;
    $maxRowIndex = count($field) - 1;
    $newField = $field;

    foreach ($field as $rowIndex => $row) {
        foreach ($row as $columnIndex => $cucumber) {
            if ($direction === DIRECTION_EAST && $cucumber === CUCUMBER_EAST) {
                $targetColumnIndex = $columnIndex === $maxColumnIndex ? 0 : $columnIndex + 1;
                if ($row[$targetColumnIndex] === CUCUMBER_NONE) {
                    $newField[$rowIndex][$columnIndex] = CUCUMBER_NONE;
                    $newField[$rowIndex][$targetColumnIndex] = CUCUMBER_EAST;
                    $hasMoved = true;
                }
            } elseif ($direction === DIRECTION_SOUTH && $cucumber === CUCUMBER_SOUTH) {
                $targetRowIndex = $rowIndex === $maxRowIndex ? 0 : $rowIndex + 1;
                if ($field[$targetRowIndex][$columnIndex] === CUCUMBER_NONE) {
                    $newField[$rowIndex][$columnIndex] = CUCUMBER_NONE;
                    $newField[$targetRowIndex][$columnIndex] = CUCUMBER_SOUTH;
                    $hasMoved = true;
                }
            }
        }
    }

    return [$newField, $hasMoved];
}

/**
 * @param string[][] $field
 * @return array
 */
function moveEast(array $field): array
{
    return move($field, DIRECTION_EAST);
}

/**
 * @param string[][] $field
 * @return array
 */
function moveSouth(array $field): array
{
    return move($field, DIRECTION_SOUTH);
}

/**
 * @param string[][] $field
 * @return array
 */
function step(array $field): array
{
    [$field, $hasMovedEast] = moveEast($field);

    [$field, $hasMovedSouth] = moveSouth($field);

    return [$field, $hasMovedEast || $hasMovedSouth];
}

/**
 * @param string[][] $field
 * @return int
 */
function getStepsUntilStop(array $field): int
{
    $hasMoved = true;
    $step = 0;

    while ($hasMoved === true) {
        [$field, $hasMoved] = step($field);
        $step++;
    }

    return $step;
}

$input = InputHelper::readFileAsStrings(INPUT_FILE);
$field = transformInput($input);

$start = microtime(true);
$stepsNeeded = getStepsUntilStop($field);
$end = microtime(true);
echo sprintf('The cucumbers stop moving after %d steps, calculated in %.3fms%s', $stepsNeeded, ($end - $start) * 1000, PHP_EOL);