<?php

declare(strict_types=1);

$text = file_get_contents($argv[1] ?? 'input.txt');
if ($text === false) {
    die('Input file is missing.' . PHP_EOL);
}

preg_match_all("/mul\((\d+),(\d+)\)/", $text, $matches, PREG_SET_ORDER);

$sum = 0;
foreach ($matches as $match) {
    $sum += (int) $match[1] * (int) $match[2];
}

echo $sum . PHP_EOL;
