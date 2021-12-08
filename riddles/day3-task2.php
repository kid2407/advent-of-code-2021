<?php
/**
 * @author Tobias Franz
 */

include_once '../InputHelper.php';

const INPUT_FILE = '../inputs/day3.txt';

$diagnosticData = InputHelper::readFileAsStrings(INPUT_FILE);

/**
 * @param array $numbers
 * @param int $relevantBit
 * @param bool $mostCommon
 * @return string[]
 */
function filterNumbers(array $numbers, int $relevantBit, bool $mostCommon = true): array
{
    $bits = array_map(function ($value) use ($relevantBit) {
        return intval(substr($value, $relevantBit, 1));
    }, $numbers);

    $totalCount      = count($bits);
    $numberOfOneBits = 0;
    foreach ($bits as $bit) {
        $numberOfOneBits += $bit;
    }

    $numberOfZeroBits = $totalCount - $numberOfOneBits;

    if ($numberOfOneBits > $numberOfZeroBits) {
        $filterBit = $mostCommon ? 1 : 0;
    } elseif ($numberOfOneBits === $numberOfZeroBits) {
        $filterBit = $mostCommon ? 1 : 0;
    } elseif ($numberOfOneBits < $numberOfZeroBits) {
        $filterBit = $mostCommon ? 0 : 1;
    } else {
        throw new RuntimeException("Something went VERY wrong!");
    }

    return array_filter($numbers, function ($value) use ($relevantBit, $filterBit) {
        return substr($value, $relevantBit, 1) == $filterBit;
    });
}

$bitCount = strlen($diagnosticData[0]);

$oxygenDiagnosticData = $diagnosticData;
$co2DiagnosticData    = $diagnosticData;
$oxygenValue          = 0;
$co2Value             = 0;

for ($counter = 0; $counter < $bitCount; $counter++) {
    $oxygenDiagnosticData = filterNumbers($oxygenDiagnosticData, $counter);
    if (count($oxygenDiagnosticData) === 1) {
        $oxygenValue = $oxygenDiagnosticData[array_key_first($oxygenDiagnosticData)];
    }

    $co2DiagnosticData = filterNumbers($co2DiagnosticData, $counter, false);
    if (count($co2DiagnosticData) === 1) {
        $co2Value = $co2DiagnosticData[array_key_first($co2DiagnosticData)];
    }
}

echo sprintf("Oxygen rating is %s, co2 rating is %s, resulting a value of %s.%s", $oxygenValue, $co2Value, bindec($oxygenValue) * bindec($co2Value), PHP_EOL);
echo "Finished running!" . PHP_EOL;