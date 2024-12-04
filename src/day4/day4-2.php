<?php

declare(strict_types=1);

$lines = file($argv[1] ?? 'input.txt', FILE_IGNORE_NEW_LINES);
if ($lines === false) {
    die('Input file is missing.' . PHP_EOL);
}

$letters = array_map('str_split', $lines);

$xmasCounter = 0;
for ($y = 0; $y < count($letters) - 2; $y++) {
    for ($x = 0; $x < count($letters[0]) - 2; $x++) {
        // diagonal 1
        $word = $letters[$y][$x] . $letters[$y + 1][$x + 1] . $letters[$y + 2][$x + 2];
        if ($word !== 'MAS' && $word !== 'SAM') {
            continue;
        }

        // diagonal 2
        $word = $letters[$y][$x + 2] . $letters[$y + 1][$x + 1] . $letters[$y + 2][$x];
        if ($word === 'MAS' || $word === 'SAM') {
            $xmasCounter += 1;
        }
    }
}

echo $xmasCounter . PHP_EOL;
