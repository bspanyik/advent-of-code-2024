<?php

declare(strict_types=1);

$text = file_get_contents($argv[1] ?? 'input.txt');
if ($text === false) {
    die('Input file is missing.' . PHP_EOL);
}

preg_match_all('/don\'t\(\)|do\(\)|mul\((\d+),(\d+)\)/', $text, $matches, PREG_SET_ORDER);

$sum = 0;
$enabled = true;
foreach ($matches as $match) {
    if ($match[0] === 'do()') {
        $enabled = true;
    } elseif ($match[0] === 'don\'t()') {
        $enabled = false;
    } elseif ($enabled && isset($match[1], $match[2])) {
        $sum += (int) $match[1] * (int) $match[2];
    }
}

echo $sum . PHP_EOL;
