<?php

declare(strict_types=1);

$input = file_get_contents($argv[1] ?? 'input.txt');
if ($input === false) {
    die('Input file is missing.' . PHP_EOL);
}

$stones = array_map(intval(...), explode(' ', $input));

for ($i = 0; $i < 25; $i++) {
    $newStones = [];
    foreach ($stones as $stone) {
        if ($stone === 0) {
            $newStones[] = 1;
            continue;
        }

        $length = strlen((string) $stone);
        if ($length % 2 === 1) {
            $newStones[] = $stone * 2024;
            continue;
        }

        $half = $length / 2;
        $newStones[] = (int) substr((string) $stone, 0, $half);
        $newStones[] = (int) substr((string) $stone, $half);
    }

    $stones = $newStones;
}

echo count($stones) . "\n";
