<?php

declare(strict_types=1);

$input = file($argv[1] ?? 'input.txt', FILE_IGNORE_NEW_LINES);
if ($input === false) {
    die('Input file is missing.' . PHP_EOL);
}

$map = [];
$starts = [];
foreach ($input as $y => $lines) {
    foreach (str_split($lines) as $x => $char) {
        $value = (int) $char;
        $map[$y][$x] = $value;
        if ($value === 0) {
            $starts[] = [$x, $y];
        }
    }
}

$sum = 0;
foreach ($starts as $start) {
    $sum += findTrails($map, ...$start);
}

echo $sum . "\n";

/** @param array<array<int>> $map */
function findTrails(array $map, int $startX, int $startY): int
{
    $height = count($map);
    $width = count($map[0]);

    $peaks = [];

    $steps[] = [$startY, $startX, 0];
    $current = 0;
    while (isset($steps[$current])) {
        [$y, $x, $value] = $steps[$current];
        $next = $value + 1;

        $ny = $y - 1;
        if ($ny >= 0 && $map[$ny][$x] === $next) {
            if ($next === 9) {
                $peaks[$ny . '|' . $x] = 1;
            } else {
                $steps[] = [$ny, $x, $next];
            }
        }

        $ny = $y + 1;
        if ($ny < $height && $map[$ny][$x] === $next) {
            if ($next === 9) {
                $peaks[$ny . '|' . $x] = 1;
            } else {
                $steps[] = [$ny, $x, $next];
            }
        }

        $nx = $x - 1;
        if ($nx >= 0 && $map[$y][$nx] === $next) {
            if ($next === 9) {
                $peaks[$y . '|' . $nx] = 1;
            } else {
                $steps[] = [$y, $nx, $next];
            }
        }

        $nx = $x + 1;
        if ($nx < $width && $map[$y][$nx] === $next) {
            if ($next === 9) {
                $peaks[$y . '|' . $nx] = 1;
            } else {
                $steps[] = [$y, $nx, $next];
            }
        }

        $current++;
    }

    return count($peaks);
}
