<?php

declare(strict_types=1);

const SEPARATOR = '   ';

$lines = file($argv[1] ?? 'input.txt', FILE_IGNORE_NEW_LINES);
if ($lines === false) {
    die('Input file is missing.' . PHP_EOL);
}

$list1 = [];
$list2 = [];

foreach ($lines as $line) {
    [$item1, $item2] = explode(SEPARATOR, $line);
    $list1[] = (int) $item1;
    $list2[] = (int) $item2;
}

$list2counter = array_count_values($list2);

$similarity = 0;
foreach ($list1 as $item) {
    $similarity += ($list2counter[$item] ?? 0) * $item;
}

echo $similarity . PHP_EOL;
