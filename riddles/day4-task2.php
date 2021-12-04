<?php
/**
 * @author Tobias Franz
 */

include_once '../InputHelper.php';

const INPUT_FILE = '../inputs/day4';

$inputData = InputHelper::readFileAsStrings(INPUT_FILE);

$drawnNumbers          = explode(',', $inputData[0]);
$totalLineNumber       = count($inputData);
$bingoFields           = [];
$currentBingoFieldLine = 0;
$currentBingoField     = 0;
$wonBoardNumbers       = [];

/**
 * @param int $number
 * @param array $bingoFields
 * @return void
 */
function markOffNumberOnBoards(int $number, array &$bingoFields)
{
    foreach ($bingoFields as $fieldKey => $bingoField) {
        foreach ($bingoField as $lineKey => $line) {
            foreach ($line as $dataKey => $data) {
                if ($data['number'] == $number) {
                    $bingoFields[$fieldKey][$lineKey][$dataKey]['found'] = true;
                }
            }
        }
    }
}

/**
 * @param array $bingoFieldData
 * @return bool
 */
function checkIfBoardHasWon(array $bingoFieldData): bool
{
    $won = checkRowsForWins($bingoFieldData);

    if (!$won) {
        $transformedField = [];
        foreach ($bingoFieldData as $row) {
            foreach ($row as $column => $value) {
                $transformedField[$column][] = $value;
            }
        }
        $won = checkRowsForWins($transformedField);
    }

    return $won;
}

function checkRowsForWins(array $bingoFieldData): bool
{
    $won = false;
    foreach ($bingoFieldData as $line) {
        if (count(array_filter($line, function ($data) {
                return $data['found'] === true;
            })) === 5) {
            $won = true;
            break;
        }
    }
    return $won;
}

/**
 * @param array $bingoField
 * @return int
 */
function getSumOfUnmarkedNumbersOfBoard(array $bingoField): int
{
    $sum = 0;

    foreach ($bingoField as $line) {
        foreach ($line as $value) {
            $sum += $value['found'] ? 0 : $value['number'];
        }
    }

    return $sum;
}

for ($lineNumber = 2; $lineNumber < $totalLineNumber; $lineNumber++) {
    $currentLine = $inputData[$lineNumber];
    if ($currentLine === '') {
        if ($currentBingoFieldLine > 0) {
            $currentBingoField++;
        }
        $currentBingoFieldLine = 0;
        continue;
    }

    $currentBingoData = preg_split('/\s+/', trim($currentLine));
    $currentBingoData = array_map(function ($value) {
        return [
            'number' => $value,
            'found'  => false,
        ];
    }, $currentBingoData);

    $bingoFields[$currentBingoField][$currentBingoFieldLine] = $currentBingoData;
    $currentBingoFieldLine++;
}

$remainingBoards = array_keys($bingoFields);
$drawnNumber     = -1;
$fieldNumber     = -1;
$winnerNumber    = -1;

while (count($remainingBoards) > 0) {
    $drawnNumber = array_shift($drawnNumbers);
    if (is_null($drawnNumber)) {
        throw new RuntimeException('Could not determine a winner!');
    }

    markOffNumberOnBoards($drawnNumber, $bingoFields);
    foreach ($bingoFields as $fieldNumber => $bingoField) {
        if (checkIfBoardHasWon($bingoField) && ($key = array_search($fieldNumber, $remainingBoards)) !== false) {
            unset ($remainingBoards[$key]);
            if (empty($remainingBoards)) {
                $winnerNumber = $fieldNumber;
            }
        }
    }
}

$remainingField = $bingoFields[$winnerNumber];
$unmarkedSum    = getSumOfUnmarkedNumbersOfBoard($remainingField);
echo sprintf("Sum for Board %d: %d%s", $winnerNumber, $unmarkedSum, PHP_EOL);
echo sprintf("Last number was %d, ersulting in the result of %d%s", $drawnNumber, $drawnNumber * $unmarkedSum, PHP_EOL);
echo "Content of Board:" . PHP_EOL . "----------" . PHP_EOL;
foreach ($remainingField as $line) {
    $inLine = false;
    foreach ($line as $value) {
        if ($inLine) {
            echo " | ";
        }
        echo sprintf("%d (%s)", $value['number'], $value['found'] ? 'X' : 'O');
        $inLine = true;
    }
    echo PHP_EOL;
}
echo "----------" . PHP_EOL;
