<?php

declare(strict_types=1);

$time = microtime(true);

$input = file($argv[1] ?? 'input.txt', FILE_IGNORE_NEW_LINES);
if ($input === false) {
    die('Input file is missing.' . PHP_EOL);
}

$sum = 0;

foreach ($input as $line) {
    [$result, $numbers] = explode(':', $line);
    $result = (int) $result;
    $numbers = array_map(intval(...), explode(' ', trim($numbers)));
    $numCount = count($numbers);

    foreach (permute('*+', $numCount - 1) as $operands) {
        $value = $numbers[0];
        for ($i = 1; $i < $numCount; $i++) {
            if ($operands[$i - 1] === '*') {
                $value *= $numbers[$i];
            } else {
                $value += $numbers[$i];
            }
        }

        if ($value === $result) {
            $sum += $result;
            break;
        }
    }
}

echo $sum . ' in ' . (microtime(true) - $time) . PHP_EOL;

function permute(string $s, int $l, array &$carry = [], array &$perms = []): array
{
    foreach (str_split($s) as $char) {
        $carry[] = $char;
        if (count($carry) === $l) {
            $perms[] = $carry;
        } else {
            permute($s, $l, $carry, $perms);
        }
        array_pop($carry);
    }

    return $perms;
}
