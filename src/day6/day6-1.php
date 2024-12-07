<?php

declare(strict_types=1);

$input = file($argv[1] ?? 'input.txt', FILE_IGNORE_NEW_LINES);
if ($input === false) {
    die('Input file is missing.' . PHP_EOL);
}

$map = [];
$gy = 0;
$gx = false;
foreach ($input as $y => $line) {
    if ($gx === false) {
        if (($pos = strpos($line, '^')) !== false) {
            $gy = $y;
            $gx = $pos;
        }
    }
    $map[] = str_split($line);
}

$dx = 0;
$dy = -1;
$path = [];

while (true) {
    $path[$gy . '|' . $gx] = 1;

    $ny = $gy + $dy;
    $nx = $gx + $dx;
    if (!isset($map[$ny][$nx])) {
        break;
    }

    if ($map[$ny][$nx] !== '#') {
        $gy = $ny;
        $gx = $nx;
    } else {
        if ($dx !== 0) {
            $dy = $dx;
            $dx = 0;
        } else {
            $dx = -1 * $dy;
            $dy = 0;
        }
        $gy = $gy + $dy;
        $gx = $gx + $dx;
    }
}

echo count($path) . PHP_EOL;
