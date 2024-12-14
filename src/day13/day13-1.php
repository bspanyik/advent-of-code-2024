<?php

declare(strict_types=1);

$input = file_get_contents(__DIR__ . "/input.txt");
if ($input === false) {
    die('Input file is missing.' . PHP_EOL);
}

$sum = 0;
foreach (explode("\n\n", $input) as $machine) {
    [$buttonA, $buttonB, $prize] = explode("\n", $machine);

    preg_match_all('/\d+/', $buttonA, $matches);
    [$aX, $aY] = array_map(intval(...), $matches[0]);

    preg_match_all('/\d+/', $buttonB, $matches);
    [$bX, $bY] = array_map(intval(...), $matches[0]);

    preg_match_all('/\d+/', $prize, $matches);
    [$prizeX, $prizeY] = array_map(intval(...), $matches[0]);

    $divs = [];
    for ($i = 0; $i < 100; $i++) {
        $restX = $prizeX - $i * $aX;
        if ($restX < 0) {
            break;
        }

        $j = $restX / $bX;
        if (is_int($j) && ($i * $aY + $j * $bY === $prizeY)) {
            $divs[] = [$i, $j, $i + $j];
        }
    }

    if ($divs === []) {
        continue;
    }

    if (count($divs) > 1) {
        usort($divs, fn(array $a, array $b) => $a[2] <=> $b[2]);
    }

    [$x, $y,] = array_shift($divs);

    $sum += 3 * $x + $y;
}

echo $sum . "\n";
