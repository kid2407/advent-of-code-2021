<?php
/**
 * @author Tobias Franz
 */

include_once '../InputHelper.php';

const INPUT_FILE = '../inputs/day21.txt';

/**
 * @param int $lastPosition
 * @return int[]
 */
function rollDeterministicDie(int $lastPosition): array
{
    $rolls = [
        ($lastPosition + 1) % 100,
        ($lastPosition + 2) % 100,
        ($lastPosition + 3) % 100,
    ];

    return [$rolls[2], array_sum($rolls)];
}

/**
 * @param int $playerOnePosition
 * @param int $playerTwoPosition
 * @return int[]
 */
function playGameWithDeterministicDie(int $playerOnePosition, int $playerTwoPosition): array
{
    $timesRolled    = 0;
    $lastDiceNumber = 0;
    $playerOneScore = 0;
    $playerTwoScore = 0;
    $playerOnesTurn = true;

    while ($playerOneScore < 1000 && $playerTwoScore < 1000) {
        [$lastDiceNumber, $sum] = rollDeterministicDie($lastDiceNumber);
        $timesRolled += 3;
        if ($playerOnesTurn) {
            $playerOnePosition = ($playerOnePosition + $sum) % 10 === 0 ? 10 : ($playerOnePosition + $sum) % 10;
            $playerOneScore    += $playerOnePosition;
            $playerOnesTurn    = false;
        } else {
            $playerTwoPosition = ($playerTwoPosition + $sum) % 10 === 0 ? 10 : ($playerTwoPosition + $sum) % 10;
            $playerTwoScore    += $playerTwoPosition;
            $playerOnesTurn    = true;
        }
    }
    return $playerOneScore >= 1000 ? [1, $timesRolled * $playerTwoScore] : [2, $timesRolled * $playerOneScore];
}

[$playerOneData, $playerTwoData] = InputHelper::readFileAsStrings(INPUT_FILE);
$playerOnePosition = intval(substr($playerOneData, 28));
$playerTwoPosition = intval(substr($playerTwoData, 28));

$start = microtime(true);
[$winner, $result] = playGameWithDeterministicDie($playerOnePosition, $playerTwoPosition);
$end = microtime(true);
echo sprintf('Playing with the deterministic die results in Player %d winning, with the result being %d in %.3fms%s', $winner, $result, ($end - $start) * 1000, PHP_EOL);