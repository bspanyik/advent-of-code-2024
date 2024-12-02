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

    if (isSafe($values)) {
        $safe += 1;
        continue;
    }

    for ($i = 0; $i < count($values); $i++) {
        $newValues = array_values(array_diff_key($values, [$i => 1]));
        if (isSafe($newValues)) {
            $safe += 1;
            break;
        }
    }
}

echo $safe . PHP_EOL;

/** @param int[] $values */
function isSafe(array $values): bool
{
    $prevDiff = 0;
    for ($i = 1; $i < count($values); $i++) {
        $currentDiff = $values[$i] - $values[$i - 1];
        if ($currentDiff === 0 || $currentDiff > 3 || $currentDiff < -3
            || ($prevDiff < 0 && $currentDiff > 0)
            || ($prevDiff > 0 && $currentDiff < 0)
        ) {
            return false;
        }

        $prevDiff = $currentDiff;
    }

    return true;
}
