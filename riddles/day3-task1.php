<?php
/**
 * @author Tobias Franz
 */

include_once '../InputHelper.php';

const INPUT_FILE = '../inputs/day3.txt';

$diagnosticData      = InputHelper::readFileAsStrings(INPUT_FILE);
$diagnosticDataCount = count($diagnosticData);
$gammaRate           = '';
$epsilonRate         = '';
$gammaRateOneBits    = [];

foreach ($diagnosticData as $line) {
    foreach (str_split($line) as $position => $bit) {
        if (!isset($gammaRateOneBits[$position])) {
            $gammaRateOneBits[$position] = 0;
        }
        if ($bit == 1) {
            $gammaRateOneBits[$position]++;
        }
    }
}

foreach ($gammaRateOneBits as $position => $gammaRateBit) {
    $moreOnesThanZeroes = $gammaRateBit > ($diagnosticDataCount / 2);
    $gammaRate          .= $moreOnesThanZeroes ? 1 : 0;
    $epsilonRate        .= $moreOnesThanZeroes ? 0 : 1;
}

echo sprintf("Gamma rate is %s, epsilon rate is %s, resulting a value of %s.%s", $gammaRate, $epsilonRate, bindec($gammaRate) * bindec($epsilonRate), PHP_EOL);
echo "Finished running!" . PHP_EOL;