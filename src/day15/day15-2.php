<?php

declare(strict_types=1);

const LF = "\n";

$input = file_get_contents(__DIR__ . "/input.txt");
if ($input === false) {
    die('Input file is missing.' . PHP_EOL);
}

[$input, $moves] = explode("\n\n", $input);

$map = [];
foreach (explode(LF, $input) as $y => $row) {
    foreach (str_split($row) as $x => $block) {
        if (!isset($robot) && $block === '@') {
            $robot = [$y, $x * 2];
            $block = '.';
        }

        if ($block === 'O') {
            $map[$y][$x * 2] = '[';
            $map[$y][$x * 2 + 1] = ']';
        } else {
            $map[$y][$x * 2] = $block;
            $map[$y][$x * 2 + 1] = $block;
        }
    }
}

if (!isset($robot)) {
    die('Warning! The robot has escaped the room.' . LF);
}

[$ry, $rx] = $robot;

foreach (str_split($moves) as $move) {
    if ($move === LF) {
        continue;
    }

    if ($move === '<' || $move === '>') {
        $rx += moveHorizontal($map, $rx, $ry, $move === '<' ? -1 : 1);
    } else {
        $ry += moveVertical($map, $rx, $ry, $move === '^' ? -1 : 1);
    }
}

echo calcSumGPS($map) . LF;

/**
 * @param string[][] $map
 */
function moveHorizontal(array &$map, int $x, int $y, int $dx): int
{
    $moving = ['.'];

    while (true) {
        $x += $dx;
        if ($map[$y][$x] === '#') {
            return 0;
        }

        if ($map[$y][$x] === '.') {
            break;
        }

        $moving[] = $map[$y][$x];
    }

    foreach (array_reverse($moving) as $block) {
        $map[$y][$x] = $block;
        $x -= $dx;
    }

    return $dx;
}

/**
 * @param string[][] $map
 */
function moveVertical(array &$map, int $x, int $y, int $dy): int
{
    $moving = [[$x]];
    while (true) {
        $y += $dy;
        $canMove = true;
        $leaders = end($moving);
        $last = count($leaders) - 1;
        $newLeaders = [];
        foreach ($leaders as $key => $x) {
            $block = $map[$y][$x];
            if ($block === '#') {
                return 0;
            }

            if ($block !== '.') {
                $canMove = false;

                if ($key === 0 && $block == ']') {
                    $newLeaders = [$x - 1, $x];
                } else {
                    $newLeaders[] = $x;
                    if ($key === $last && $block === '[') {
                        $newLeaders[] = $x + 1;
                    }
                }
            }
        }

        if ($canMove) {
            break;
        }

        $moving[] = $newLeaders;
    }

    $moving = array_reverse($moving);
    foreach ($moving as $row) {
        $ny = $y - $dy;
        foreach ($row as $x) {
            $map[$y][$x] = $map[$ny][$x];
            $map[$ny][$x] = '.';
        }
        $y = $ny;
    }

    return $dy;
}

/** @param string[][] $map */
function calcSumGPS(array $map): int
{
    $sum = 0;
    foreach ($map as $y => $row) {
        foreach ($row as $x => $block) {
            if ($block === '[') {
                $sum += 100 * $y + $x;
            }
        }
    }

    return $sum;
}
