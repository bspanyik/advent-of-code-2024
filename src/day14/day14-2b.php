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
while ($step < 10000) {
    $step += 1;
    $im = imagecreate(WIDTH, HEIGHT) or die('PHP\'s gd extension is not installed.');
    imagecolorallocate($im, 255, 255, 255);

    $black = (int) imagecolorallocate($im, 0, 0, 0);
    foreach ($robots as [$rx, $ry, $vx, $vy]) {
        $x = (($rx + $vx * $step) % WIDTH + WIDTH) % WIDTH;
        $y = (($ry + $vy * $step) % HEIGHT + HEIGHT) % HEIGHT;
        imagesetpixel($im, $x, $y, $black);
    }
    imagepng($im, "images/$step.png");
}
