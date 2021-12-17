<?php
/**
 * @author Tobias Franz
 */

include_once '../InputHelper.php';

const INPUT_FILE = '../inputs/day17.txt';

/**
 * @param string $line
 * @return array
 */
function getTargetCoordinates(string $line): array
{
    $data = array_map(function ($element) {
        return array_map('intval', explode('..', substr($element, 2)));
    }, explode(', ', substr($line, 13)));

    return [
        'minX' => min($data[0]),
        'maxX' => max($data[0]),
        'minY' => min($data[1]),
        'maxY' => max($data[1]),
    ];
}

/**
 * @param int $velocityX
 * @param int $velocityY
 * @param array $targetArea
 * @return int|null The height it reaches, or null if it does not hit the target
 */
function shootProbe(int $velocityX, int $velocityY, array $targetArea): ?int
{
    $maxHeight = 0;
    $positionX = 0;
    $positionY = 0;
    $onTarget  = false;

    while ($positionX <= $targetArea['maxX'] && $positionY >= $targetArea['minY']) {
        $positionX += $velocityX;
        $positionY += $velocityY;
        $velocityX += $velocityX > 0 ? -1 : ($velocityX === 0 ? 0 : 1);
        $velocityY -= 1;
        $maxHeight = $positionY > $maxHeight ? $positionY : $maxHeight;

        if ($positionX >= $targetArea['minX'] && $positionX <= $targetArea['maxX'] && $positionY >= $targetArea['minY'] && $positionY <= $targetArea['maxY']) {
            $onTarget = true;
            break;
        }
    }

    return $onTarget ? $maxHeight : null;
}

$line       = InputHelper::readFileAsString(INPUT_FILE);
$targetArea = getTargetCoordinates($line);

// Task 1
$start         = microtime(true);
$maxHeight     = 0;
$bestXVelocity = 0;
$bestYVelocity = 0;
for ($vx = 1; $vx < $targetArea['maxX']; $vx++) {
    for ($vy = min(0, $targetArea['minY']); $vy < $targetArea['maxX']; $vy++) {
        $height = shootProbe($vx, $vy, $targetArea);
        if ($height > $maxHeight) {
            $maxHeight     = $height;
            $bestXVelocity = $vx;
            $bestYVelocity = $vy;
        }
    }
}
$end = microtime(true);
echo sprintf("Maximum height was %d reached with (%d|%d) in %.3fms%s", $maxHeight, $bestXVelocity, $bestYVelocity, ($end - $start) * 1000, PHP_EOL);

// Task 1
$start         = microtime(true);
$onTargetCount = 0;
for ($vx = 1; $vx <= $targetArea['maxX']; $vx++) {
    for ($vy = $targetArea['minY']; $vy <= $targetArea['maxX']; $vy++) {
        if (!is_null(shootProbe($vx, $vy, $targetArea))) {
            $onTargetCount++;
        }
    }
}
$end = microtime(true);
echo sprintf("Maximum number of velocities on target is %d reached in %.3fms%s", $onTargetCount, ($end - $start) * 1000, PHP_EOL);