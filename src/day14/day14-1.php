<?php

declare(strict_types=1);

const WIDTH = 101;
const HEIGHT = 103;

const MID_X = 50;
const MID_Y = 51;

const STEPS = 100;

$input = file_get_contents(__DIR__ . "/input.txt");
if ($input === false) {
    die('Input file is missing.' . PHP_EOL);
}

$quadrant = [0, 0, 0, 0];
foreach (explode("\n", $input) as $robot) {
    preg_match('/p=(\d+),(\d+) v=(-?\d+),(-?\d+)/', $robot, $matches);
    if (count($matches) < 5) {
        continue;
    }

    $matches = array_map(intval(...), $matches);
    [,$rx, $ry, $vx, $vy] = $matches;

    $x = (($rx + $vx * STEPS) % WIDTH + WIDTH) % WIDTH;
    $y = (($ry + $vy * STEPS) % HEIGHT + HEIGHT) % HEIGHT;

    if ($x !== MID_X && $y !== MID_Y) {
        $q = ($x > MID_X ? 1 : 0) + ($y > MID_Y ? 2 : 0);
        $quadrant[$q] += 1;
    }
}

echo array_product($quadrant) . "\n";
