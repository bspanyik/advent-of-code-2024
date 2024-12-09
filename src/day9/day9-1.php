<?php

declare(strict_types=1);

$input = file_get_contents($argv[1] ?? 'input.txt');
if ($input === false) {
    die('Input file is missing.' . PHP_EOL);
}

//$input = '2333133121414131402';

$input = array_map(intval(...), str_split($input));

$sum = 0;
$endStack = [];
$endId = floor(count($input) / 2);
$i = 0;
$pos = 0;

while (isset($input[$i])) {
    if ($i % 2 === 0) {
        $id = $i / 2;
        $value = $input[$i];
        while ($value > 0) {
            $sum += $pos * $id;
            $pos++;
            $value--;
        }
    } else {
        $free = $input[$i];
        while ($free > 0) {
            // fill up the end stack from the back
            if ($endStack === []) {
                $last = array_pop($input) ?? 0;
                $endStack = array_fill(0, $last, $endId);
                $endId--;
                array_pop($input); // free space before previous last
            }

            $id = array_pop($endStack);
            $sum += $pos * $id;
            $pos++;
            $free--;
        }
    }

    $i++;
}

while ($endStack !== []) {
    $id = array_pop($endStack);
    $sum += $pos * $id;
    $pos++;
}

echo $sum . "\n";
