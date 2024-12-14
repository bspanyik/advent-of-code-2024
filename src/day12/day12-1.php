<?php

declare(strict_types=1);

const DIRECTIONS = [
    [-1, 0],
    [1, 0],
    [0, -1],
    [0, 1],
];

$input = file($argv[1] ?? 'input.txt', FILE_IGNORE_NEW_LINES);
if ($input === false) {
    die('Input file is missing.' . PHP_EOL);
}

$map = array_map(str_split(...), $input);

$width = count($map[0]);
$height = count($map);

$regions = [];
$taken = [];
$fencingPrice = 0;
for ($y = 0; $y < $height; $y++) {
    for ($x = 0; $x < $width; $x++) {
        if (isset($taken[$y . '|' . $x])) {
            continue;
        }

        $region = findRegion($map, $y, $x);
        $perimeter = calcPerimeter($region, $map);
        $fencingPrice += count($region) * $perimeter;

        foreach ($region as [$ry, $rx]) {
            $taken[ $ry . '|' . $rx] = 1;
        }
    }
}

echo $fencingPrice . "\n";

/**
 * @param string[][] $map
 * @return int[][]
 */
function findRegion(array $map, int $y, int $x): array
{
    $block = $map[$y][$x];
    $region = [[$y, $x]];
    $check = [[$y, $x]];

    while ($check !== []) {
        [$ry, $rx] = array_pop($check);
        foreach (DIRECTIONS as [$dy, $dx]) {
            $ny = $ry + $dy;
            $nx = $rx + $dx;
            if (isset($map[$ny][$nx]) && $map[$ny][$nx] === $block && !in_array([$ny, $nx], $region)) {
                $region[] = [$ny, $nx];
                $check[] = [$ny, $nx];
            }
        }
    }

    return $region;
}

/**
 * @param int[][] $region
 * @param string[][] $map
 */
function calcPerimeter(array $region, array $map): int
{
    $perimeter = 0;
    foreach ($region as [$ry, $rx]) {
        foreach (DIRECTIONS as [$dy, $dx]) {
            $ny = $ry + $dy;
            $nx = $rx + $dx;
            if (!isset($map[$ny][$nx]) || $map[$ny][$nx] !== $map[$ry][$rx]) {
                $perimeter += 1;
            }
        }
    }

    return $perimeter;
}
