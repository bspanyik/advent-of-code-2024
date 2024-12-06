<?php

declare(strict_types=1);

$input = file_get_contents($argv[1] ?? 'input.txt');
if ($input === false) {
    die('Input file is missing.' . "\n");
}

[$rules, $updates] = explode("\n\n", $input);

$rules = array_flip(explode("\n", $rules));

$updates = array_map(
    static fn(string $pages) => explode(',', $pages),
    explode("\n", $updates),
);

$sumMiddlePages = 0;
foreach ($updates as $pages) {
    $sorted = $pages;
    usort($sorted, static fn(string $a, string $b): int => isset($rules[$a . '|' . $b]) ? -1 : 1);
    if ($sorted !== $pages) {
        $sumMiddlePages += (int) $sorted[intval(count($sorted) / 2)];
    }
}

echo $sumMiddlePages . "\n";
