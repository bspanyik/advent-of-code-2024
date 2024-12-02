<?php

declare(strict_types=1);

const SEPARATOR = ' ';

$lines = file($argv[1] ?? 'input.txt', FILE_IGNORE_NEW_LINES);
if ($lines === false) {
    die('Input file is missing.' . PHP_EOL);
}

$safe = 0;
foreach ($lines as $key => $line) {
    $values = array_map(intval(...), explode(SEPARATOR, $line));
    $isSafe = true;
    $prevDiff = 0;
    for ($i = 1; $isSafe && $i < count($values); $i++) {
        $currentDiff = $values[$i] - $values[$i - 1];
        if ($currentDiff === 0 || $currentDiff > 3 || $currentDiff < -3
            || ($prevDiff < 0 && $currentDiff > 0)
            || ($prevDiff > 0 && $currentDiff < 0)
        ) {
            $isSafe = false;
        }

        $prevDiff = $currentDiff;
    }

    if ($isSafe) {
        $safe += 1;
    }
}

echo $safe . PHP_EOL;
