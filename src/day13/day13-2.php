<?php

declare(strict_types=1);

$input = file_get_contents(__DIR__ . "/input.txt");
if ($input === false) {
    die('Input file is missing.' . PHP_EOL);
}

/*
$input = <<< EOT
Button A: X+94, Y+34
Button B: X+22, Y+67
Prize: X=8400, Y=5400

Button A: X+26, Y+66
Button B: X+67, Y+21
Prize: X=12748, Y=12176

Button A: X+17, Y+86
Button B: X+84, Y+37
Prize: X=7870, Y=6450

Button A: X+69, Y+23
Button B: X+27, Y+71
Prize: X=18641, Y=10279
EOT;

/*

Button A: X+94, Y+34
Button B: X+22, Y+67
Prize: X=8400, Y=5400

a * 94 + b * 22 = 8400
a * 34 + b * 67 = 5400

34a + 67b = 5400
34a = 5400 - 67b
a = (5400 - 67b) / 34

     (5400 - 67 b)
94 * ------------- + 22 b = 8400
           34

94 * 5400 - 94 * 67 * b + 34 * 22 * b = 34 * 8400

a aX + b bX = prizeX
a aY + b bY = prizeY

a = (prizeY - b bY) / aY


( aX * prizeY - ax * by * b ) / aY + b bX = prizeX

aX * prizeY - ax * by * b + ay * bx * b = ay * prizeX


aX * prizeY - aX * bY * b + aY * bX * b = aY * prizeX

     (aX * prizeY - aY * prizeX)
b = ------------------------------
         aX * bY - aY * $bX


507600 - 6298 b + 748 b = 285600

6298 b - 748 b = 507 600 - 285 600

5550 b = 222 000

b = 40
*/

$sum = 0;
foreach (explode("\n\n", $input) as $machine) {
    [$buttonA, $buttonB, $prize] = explode("\n", $machine);

    preg_match_all('/\d+/', $buttonA, $matches);
    [$aX, $aY] = array_map(intval(...), $matches[0]);

    preg_match_all('/\d+/', $buttonB, $matches);
    [$bX, $bY] = array_map(intval(...), $matches[0]);

    preg_match_all('/\d+/', $prize, $matches);
    [$prizeX, $prizeY] = array_map(intval(...), $matches[0]);

    $prizeX += 10000000000000;
    $prizeY += 10000000000000;

    $d1 = $aX * $prizeY - $aY * $prizeX;
    $d2 = $aX * $bY - $aY * $bX;

    $b = $d1 / $d2;
    if (!is_int($b)) {
        continue;
    }

    $a = ($prizeY - $bY * $b) / $aY;
    if (!is_int($a)) {
        continue;
    }

    $sum += 3 * $a + $b;
}

echo $sum . "\n";
