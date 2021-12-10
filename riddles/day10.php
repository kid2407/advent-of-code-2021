<?php
/**
 * @author Tobias Franz
 */

include_once '../InputHelper.php';

const INPUT_FILE = '../inputs/day10.txt';

/**
 * @param string $line
 * @return string|null
 */
function getInvalidCharacterFromLine(string $line): ?string
{
    $stack = [];

    $invalidChar = null;
    $chars       = str_split($line);
    foreach ($chars as $char) {
        if ($char === '(') {
            $stack[] = 'round';
        } elseif ($char === ')') {
            if (end($stack) === 'round') {
                array_pop($stack);
            } else {
                $invalidChar = $char;
                break;
            }
        } elseif ($char === '[') {
            $stack[] = 'square';
        } elseif ($char === ']') {
            if (end($stack) === 'square') {
                array_pop($stack);
            } else {
                $invalidChar = $char;
                break;
            }
        } elseif ($char === '{') {
            $stack[] = 'curly';
        } elseif ($char === '}') {
            if (end($stack) === 'curly') {
                array_pop($stack);
            } else {
                $invalidChar = $char;
                break;
            }
        } elseif ($char === '<') {
            $stack[] = 'angled';
        } elseif ($char === '>') {
            if (end($stack) === 'angled') {
                array_pop($stack);
            } else {
                $invalidChar = $char;
                break;
            }
        }
    }

    return $invalidChar;
}

/**
 * @param string $line
 * @return string
 */
function getMissingSequenceForLine(string $line): string
{
    $stack = [];

    $chars = str_split($line);
    foreach ($chars as $char) {
        if ($char === '(') {
            $stack[] = 'round';
        } elseif ($char === ')') {
            array_pop($stack);
        } elseif ($char === '[') {
            $stack[] = 'square';
        } elseif ($char === ']') {
            array_pop($stack);
        } elseif ($char === '{') {
            $stack[] = 'curly';
        } elseif ($char === '}') {
            array_pop($stack);
        } elseif ($char === '<') {
            $stack[] = 'angled';
        } elseif ($char === '>') {
            array_pop($stack);
        }
    }

    $missingSequence = '';
    foreach (array_reverse($stack) as $element) {
        if ($element === 'round') {
            $missingSequence .= ')';
        } elseif ($element === 'square') {
            $missingSequence .= ']';
        } elseif ($element === 'curly') {
            $missingSequence .= '}';
        } elseif ($element === 'angled') {
            $missingSequence .= '>';
        }
    }

    return $missingSequence;
}

/**
 * @param int[] $invalidChars
 * @return int
 */
function calculatePointsForMissingCharacters(array $invalidChars): int
{
    $points     = 0;
    $charValues = [
        ')' => 3,
        ']' => 57,
        '}' => 1197,
        '>' => 25137,
    ];

    foreach ($invalidChars as $invalidChar) {
        $points += $charValues[$invalidChar] ?? 0;
    }

    return $points;
}

/**
 * @param string[] $incompleteLines
 * @return int
 */
function calculatePointsForCompletions(array $incompleteLines): int
{
    $scores = [];
    foreach ($incompleteLines as $incompleteLine) {
        $localScore      = 0;
        $missingSequence = getMissingSequenceForLine($incompleteLine);
        foreach (str_split($missingSequence) as $char) {
            $localScore *= 5;
            $localScore += $char === ')' ? 1 : ($char === ']' ? 2 : ($char === '}' ? 3 : ($char === '>' ? 4 : 0)));
        }
        $scores [] = $localScore;
    }

    sort($scores);

    return $scores[(count($scores) - 1) / 2];
}

$lines = InputHelper::readFileAsStrings(INPUT_FILE);

// Task 1
$start             = microtime(true);
$invalidCharacters = [];
$incompleteLines   = [];
foreach ($lines as $line) {
    $invalidCharacter = getInvalidCharacterFromLine($line);
    if (!is_null($invalidCharacter)) {
        $invalidCharacters[] = $invalidCharacter;
    } else {
        $incompleteLines[] = $line;
    }
}
$points = calculatePointsForMissingCharacters($invalidCharacters);
$end    = microtime(true);
echo sprintf('Found a total of %d invalid characters with %d points in %.3fms%s', count($invalidCharacters), $points, ($end - $start) * 1000, PHP_EOL);

// Task 2
$start                  = microtime(true);
$missingSequencesPoints = calculatePointsForCompletions($incompleteLines);
$end                    = microtime(true);
echo sprintf('Found a total of %d invalid characters with %d points in %.3fms%s', count($incompleteLines), $missingSequencesPoints, ($end - $start) * 1000, PHP_EOL);