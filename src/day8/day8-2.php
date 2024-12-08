<?php

declare(strict_types=1);

$input = file($argv[1] ?? 'input.txt', FILE_IGNORE_NEW_LINES);
if ($input === false) {
    die('Input file is missing.' . PHP_EOL);
}

$freqs = [];
foreach ($input as $y => $line) {
    foreach (str_split($line) as $x => $freq) {
        $map[$y][$x] = $freq;
        if ($freq !== '.') {
            if (!isset($freqs[$freq])) {
                $freqs[$freq] = [];
            }
            $freqs[$freq][] = [$y, $x];
        }
    }
}

$antinodes = [];
foreach ($freqs as $antennas) {
    $count = count($antennas);
    for ($i = 0; $i < $count - 1; $i++) {
        [$y1, $x1] = $antennas[$i];
        for ($j = $i + 1; $j < $count; $j++) {
            [$y2, $x2] = $antennas[$j];

            $dy = $y1 - $y2;
            $dx = $x1 - $x2;

            $y = $y1;
            $x = $x1;
            do {
                $antinodes[$y . '|' . $x] = 1;
                $y += $dy;
                $x += $dx;
            } while (isset($map[$y][$x]));

            $y = $y2;
            $x = $x2;
            do {
                $antinodes[$y . '|' . $x] = 1;
                $y -= $dy;
                $x -= $dx;
            } while (isset($map[$y][$x]));
        }
    }
}
echo count($antinodes) . PHP_EOL;
