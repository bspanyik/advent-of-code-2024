<?php

declare(strict_types=1);

const WIDTH = 101;
const HEIGHT = 103;
const MID_X = 50;
const MID_Y = 51;

$input = file_get_contents(__DIR__ . "/input.txt");
if ($input === false) {
    die('Input file is missing.' . PHP_EOL);
}

$robots = [];
foreach (explode("\n", $input) as $robot) {
    preg_match('/p=(\d+),(\d+) v=(-?\d+),(-?\d+)/', $robot, $matches);
    if (count($matches) < 5) {
        continue;
    }

    $robots[] = array_map(intval(...), $matches);
}

$step = 0;
$minSafetyFactor = PHP_INT_MAX;
$minStep = PHP_INT_MAX;
while ($step < 10000) {
    $step += 1;
    $quadrants = moveRobots($robots, $step);
    $safetyFactor = array_product($quadrants);
    if ($safetyFactor < $minSafetyFactor) {
        $minStep = $step;
        $minSafetyFactor = $safetyFactor;
    }
}

echo $minStep . ', ' . $minSafetyFactor . "\n";

/**
 * @param int[][] $robots
 * @return int[]
 */
function moveRobots(array $robots, int $step): array
{
    $quadrants = [0, 0, 0, 0];
    foreach ($robots as [$rx, $ry, $vx, $vy]) {
        $x = (($rx + $vx * $step) % WIDTH + WIDTH) % WIDTH;
        $y = (($ry + $vy * $step) % HEIGHT + HEIGHT) % HEIGHT;

        if ($x !== MID_X && $y !== MID_Y) {
            $q = ($x > MID_X ? 1 : 0) + ($y > MID_Y ? 2 : 0);
            $quadrants[$q] += 1;
        }
    }

    return $quadrants;
}
