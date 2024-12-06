<?php

declare(strict_types=1);

$input = file_get_contents($argv[1] ?? 'input.txt');
if ($input === false) {
    die('Input file is missing.' . "\n");
}

[$rules, $updates] = explode("\n\n", $input);

$pageRules = [];
foreach (explode("\n", $rules) as $rule) {
    [$before, $after] = explode('|', $rule);
    if (!isset($pageRules[$before])) {
        $pageRules[$before] = [];
    }
    $pageRules[$before][] = $after;
}

$updates = array_map(
    static fn(string $pages) => explode(',', $pages),
    explode("\n", $updates),
);

$sumMiddlePages = 0;
foreach ($updates as $pages) {
    $middleNumber = (int) $pages[intval(count($pages) / 2)];
    $pages = array_flip($pages);
    foreach ($pages as $page => $index) {
        foreach ($pageRules[$page] ?? [] as $after) {
            if (isset($pages[$after]) && $pages[$after] < $index) {
                continue 3;
            }
        }
    }
    $sumMiddlePages += $middleNumber;
}

echo $sumMiddlePages . "\n";
