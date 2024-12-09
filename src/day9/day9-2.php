<?php

declare(strict_types=1);

$time = microtime(true);

$input = file_get_contents($argv[1] ?? 'input.txt');
if ($input === false) {
    die('Input file is missing.' . PHP_EOL);
}

// $input = '2333133121414131402';

$input = array_map(intval(...), str_split($input));
$input[] = 0;

$count = count($input);
$files = [];
$frees = [];
$disk = [];
$pos = 0;

for ($i = 0; $i < count($input); $i++) {
    $id = $i / 2;
    $value = $input[$i];
    $files[$id] = [$pos, $value];
    while ($value > 0) {
        $disk[$pos] = $id;
        $value--;
        $pos++;
    }

    $i++;
    $value = $input[$i];
    $frees[] = ['pos' => $pos, 'length' => $value];
    while ($value > 0) {
        $disk[$pos] = 0;
        $value--;
        $pos++;
    }
}

// the first value is fix
unset($files[0]);
$files = array_reverse($files, true);

foreach ($files as $id => [$filePos, $fileLength]) {
    for ($i = 0; $i < count($frees); $i++) {
        if ($frees[$i]['pos'] > $filePos) {
            break;
        }

        if ($frees[$i]['length'] >= $fileLength) {
            $pos = $frees[$i]['pos'];
            for ($j = 0; $j < $fileLength; $j++) {
                $disk[$pos + $j] = $id;
            }
            $frees[$i]['length'] -= $fileLength;
            $frees[$i]['pos'] += $fileLength;
            $disk = array_replace($disk, array_fill($filePos, $fileLength, 0));
            break;
        }
    }
}

$sum = 0;
foreach ($disk as $pos => $mul) {
    $sum += $pos * $mul;
}

echo $sum . ' in ' . (microtime(true) - $time) . "\n";
