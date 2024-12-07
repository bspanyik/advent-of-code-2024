<?php

declare(strict_types=1);

$input = file($argv[1] ?? 'input.txt', FILE_IGNORE_NEW_LINES);
if ($input === false) {
    die('Input file is missing.' . PHP_EOL);
}

$time = microtime(true);
$map = [];
$gy = 0;
$gx = -1;
foreach ($input as $y => $line) {
    if ($gx === -1) {
        if (($pos = strpos($line, '^')) !== false) {
            $gy = $y;
            $gx = $pos;
        }
    }
    $map[] = str_split($line);
}

$dx = 0;
$dy = -1;
$startPos = [$gy, $gx, $dy, $dx];

$path = [];
while (true) {
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

    $path[$gy . '|' . $gx] = 1;
}
echo count($path) . ' in ' . (microtime(true) - $time) . PHP_EOL;

$path = array_map(
    fn(string $s): array => array_map(
        intval(...),
        explode('|', $s),
    ),
    array_keys($path),
);

$blocks = 0;
foreach ($path as [$by, $bx]) {
    $newMap = $map;
    $newMap[$by][$bx] = '#';
    if (isGuardInLoop($newMap, ...$startPos)) {
        $blocks++;
    }
}

echo $blocks . ' in ' . (microtime(true) - $time) . PHP_EOL;

/** @param array<int, array<int, string>> $map */
function isGuardInLoop(array $map, int $gy, int $gx, int $dy, int $dx): bool
{
    $path = [];

    while (true) {
        $ny = $gy + $dy;
        $nx = $gx + $dx;
        if (!isset($map[$ny][$nx])) {
            return false;
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
//            $gy += $dy;
//            $gx += $dx;
        }

        if (isset($path[$gy][$gx][$dy][$dx])) {
            return true;
        }

        $path[$gy][$gx][$dy][$dx] = 1;
    }
}

