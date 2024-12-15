<?php

declare(strict_types=1);

const MOVES = [
    '^' => [-1, 0],
    'v' => [1, 0],
    '<' => [0, -1],
    '>' => [0, 1],
];

$input = file_get_contents(__DIR__ . "/input.txt");
if ($input === false) {
    die('Input file is missing.' . PHP_EOL);
}

[$input, $moves] = explode("\n\n", $input);

$map = [];
foreach (explode("\n", $input) as $y => $row) {
    foreach (str_split($row) as $x => $block) {
        if ($block === '@') {
            $robot = [$y, $x];
            $map[$y][$x] = '.';
        } else {
            $map[$y][$x] = $block;
        }
    }
}
if (!isset($robot)) {
    die('Warning! The robot has escaped the room.' . "\n");
}

[$ry, $rx] = $robot;

foreach (str_split($moves) as $move) {
    if ($move === "\n") {
        continue;
    }

    [$dy, $dx] = MOVES[$move];

    $y = $ry + $dy;
    $x = $rx + $dx;

    $count = 1;
    $canMove = true;

    while (true) {
        if ($map[$y][$x] === '.') {
            break;
        }

        if ($map[$y][$x] === '#') {
            $canMove = false;
            break;
        }

        $count += 1;
        $y += $dy;
        $x += $dx;
    }

    if ($canMove) {
        while ($count > 1) {
            $map[$y][$x] = 'O';
            $y -= $dy;
            $x -= $dx;
            $count--;
        }
        $map[$y][$x] = '.';
        $ry = $y;
        $rx = $x;
    }
}
echo calcSumGPS($map) . "\n";

/** @param string[][] $map */
function calcSumGPS(array $map): int
{
    $sum = 0;
    foreach ($map as $y => $row) {
        foreach ($row as $x => $block) {
            if ($block === 'O') {
                $sum += 100 * $y + $x;
            }
        }
    }

    return $sum;
}
