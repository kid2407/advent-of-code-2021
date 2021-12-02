<?php
/**
 * @author Tobias Franz
 */

include_once '../InputHelper.php';

const INPUT_FILE = '../inputs/day2';

$commands = InputHelper::readFileAsStrings(INPUT_FILE);
$position = 0;
$depth    = 0;
$aim      = 0;

foreach ($commands as $command) {
    [$action, $number] = explode(' ', $command);
    switch ($action) {
        case 'forward':
            $position += $number;
            $depth    += $number * $aim;
            break;
        case 'up':
            $aim -= $number;
            break;
        case 'down':
            $aim += $number;
            break;
        default:
            break;
    }
}

echo sprintf("Position is %d, Depth is %d, resulting a value of %d.%s", $position, $depth, $position * $depth, PHP_EOL);
echo "Finished running!" . PHP_EOL;