<?php

declare(strict_types=1);

$lines = file($argv[1] ?? 'input.txt', FILE_IGNORE_NEW_LINES);
if ($lines === false) {
    die('Input file is missing.' . PHP_EOL);
}

$letters = array_map('str_split', $lines);

$bottomEdge = count($letters) - 4;
$rightEdge = count($letters[0]) - 4;

$xmasCounter = 0;
for ($y = 0; $y < count($letters); $y++) {
    for ($x = 0; $x < count($letters[0]); $x++) {
        $letter = $letters[$y][$x];
        if ($letter !== 'X' && $letter !== 'S') {
            continue;
        }

        // check horizontal
        if ($x <= $rightEdge) {
            $xmasCounter += (int) isXmas(implode('', array_slice($letters[$y], $x, 4)));

            // diagonal up
            if ($y >= 3) {
                $xmasCounter += (int) isXmas($letter . $letters[$y - 1][$x + 1] . $letters[$y - 2][$x + 2] . $letters[$y - 3][$x + 3]);
            }

            // diagonal down
            if ($y <= $bottomEdge) {
                $xmasCounter += (int) isXmas($letter . $letters[$y + 1][$x + 1] . $letters[$y + 2][$x + 2] . $letters[$y + 3][$x + 3]);
            }
        }

        // vertical
        if ($y <= $bottomEdge) {
            $xmasCounter += (int) isXmas($letter . $letters[$y + 1][$x] . $letters[$y + 2][$x] . $letters[$y + 3][$x]);
        }
    }
}

echo $xmasCounter . PHP_EOL;

function isXmas(string $word): bool
{
    return $word === 'XMAS' || $word === 'SAMX';
}
