<?php

declare(strict_types=1);

const DIRECTIONS = [
    'U' => [-1, 0],
    'D' => [1, 0],
    'L' => [0, -1],
    'R' => [0, 1],
];

$input = file($argv[1] ?? 'input.txt', FILE_IGNORE_NEW_LINES);
if ($input === false) {
    die('Input file is missing.' . PHP_EOL);
}

/*
$input = <<< EOT
AAAA
BBCD
BBCC
EEEC
EOT;

$input = <<< EOT
OOOOO
OXOXO
OOOOO
OXOXO
OOOOO
EOT;

$input = <<< EOT
EEEEE
EXXXX
EEEEE
EXXXX
EEEEE
EOT;

$input = <<< EOT
AAAAAA
AAABBA
AAABBA
ABBAAA
ABBAAA
AAAAAA
EOT;

$input = <<< EOT
RRRRIICCFF
RRRRIICCCF
VVRRRCCFFF
VVRCCCJFFF
VVVVCJJCFE
VVIVCCJJEE
VVIIICJJEE
MIIIIIJJEE
MIIISIJEEE
MMMISSJEEE
EOT;

$input = explode("\n", $input);
*/

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
        $sides = calcSides($region, $map);
        $price = count($region) * $sides;

        $fencingPrice += $price;

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
function calcSides(array $region, array $map): int
{
    usort(
        $region,
        fn(array $c1, array $c2) => $c1[0] === $c2[0] ? $c1[1] <=> $c2[1] : $c1[0] <=> $c2[0],
    );

    $walls = [];
    foreach ($region as [$ry, $rx]) {
        foreach (DIRECTIONS as $dir => [$dy, $dx]) {
            $y = $ry + $dy;
            $x = $rx + $dx;
            if (!in_array([$y, $x], $region)) {
                if ($dir === 'U' || $dir === 'D') {
                    if (!isset($walls[$dir][$y])) {
                        $walls[$dir][$y] = [];
                    }
                    $walls[$dir][$y][] = $x;
                } else {
                    if (!isset($walls[$dir][$x])) {
                        $walls[$dir][$x] = [];
                    }
                    $walls[$dir][$x][] = $y;
                }
            }
        }
    }

    $sides = 0;
    foreach ($walls as $dir) {
        foreach ($dir as $pieces) {
            $sides += 1;
            $prev = $pieces[0];
            for ($i = 1; $i < count($pieces); $i++) {
                $curr = $pieces[$i];
                if ($curr - $prev > 1) {
                    $sides += 1;
                }
                $prev = $curr;
            }
        }
    }

    return $sides;
}
