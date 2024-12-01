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

sort($list1);
sort($list2);

$differences = 0;
for ($i = 0; $i < count($list1); $i++) {
    $differences += abs($list1[$i] - $list2[$i]);
}

echo $differences . PHP_EOL;
