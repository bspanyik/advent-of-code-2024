<?php

declare(strict_types=1);

const WIDTH = 101;
const HEIGHT = 103;

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

$robotCount = count($robots);

$step = 0;
while (true) {
    $step += 1;
    $pos = [];
    foreach ($robots as [$rx, $ry, $vx, $vy]) {
        $x = (($rx + $vx * $step) % WIDTH + WIDTH) % WIDTH;
        $y = (($ry + $vy * $step) % HEIGHT + HEIGHT) % HEIGHT;
        $pos["$x,$y"] = 1;
    }
    if (count($pos) === $robotCount) {
        break;
    }
}

for ($y = 0; $y < HEIGHT; $y++) {
    for ($x = 0; $x < WIDTH; $x++) {
        echo isset($pos["$x,$y"]) ? '#' : ' ';
    }
    echo "\n";
}
