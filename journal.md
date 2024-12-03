# My thoughts along the way


## [Day 3: Mull It Over](https://adventofcode.com/2024/day/3) - Darn regular expressions!

I hate regular expressions. Don't know why.

Every time I know I have to deal with regular expressions, I also know it's not going to be smooth sailing. This time I knew I had to use `preg_match_all`, I just didn't have the slightest idea how it worked. So [I looked it up in the PHP docs page](https://www.php.net/manual/en/function.preg-match-all.php), and from the examples there I came to the conclusion that I could not only extract the whole `mul(x,y)` expression, but `x` and `y` as well. Easy peasy, lemon squeezie.

For the second part I first tried to `preg_split` the text on `don't()+anything+do()`, then `implode` the rest and run part 1 on it, but it didn't work. Don't know why. J think it should have, so I might revisit this if I'll have time. But another example on the `preg_match_all` page came to the rescue: you can match several things at the same time, separated by the `pipe` character. Great. I just had to `var_dump` the results, to know how to handle it, and I was almost done, right?

Yeah, almost. First came 30 minutes of "why on the frigging earth it doesn't work?!" debugging session. The realization that I was running the `for` cycle on the size of the `$matches` array instead of the size of the first element of the array, `$matches[0]`, where the real matches were, came, again, painfully late. :shrug:

Well, at least I learned how to use `preg_match_all` today. Too bad, I'll forget it by next week.

---
**UPDATE [10 minutes later]:** Oh. My. God. :O **PREG_SET_ORDER**!!! [Check this out!](https://www.php.net/manual/en/pcre.constants.php#constant.preg-set-order)

> Orders results so that `$matches[0]` is an array of first set of matches, `$matches[1]` is an array of second set of matches, and so on.

Mind blown! You can simply `foreach` on the matches. The whole `for` cycle fiasco would never have happened! :facepalm:

Also strange behaviour from `phpstan` today: it says `Binary operation "*" between ''|numeric-string and ''|numeric-string results in an error.`. It's right, but it only found this in the solution of part 2, not in part 1. :thinking: I've fixed both.


## [Day 2: Red-Nosed Reports](https://adventofcode.com/2024/day/2) - Unforeseen difficulties

I did not expect to have serious problems on the second day, but I did. I tried to rush through the first part and made a lot of stupid mistakes. The main question was how to tell if the sequence of numbers was increasing or decreasing. PHP doesn't have a sign function, although it can be implemented with the spaceship operator (<=>), I eventually decided to store the previous difference instead and compare it to the current one to see if they were on the same side of 0. The first mistake I made was not reading the problem properly (I fear this will be a recurring theme in the future). I thought that only 1 and 2 were the allowed differences. I even managed to code this badly, I copy this bit here because it's so beautiful:
```
if ($currentDiff === 0 || $currentDiff > 2 || $currentDiff < 2) {
    $isSafe = false;
}
```
Yeah, that bad. :facepalm:

So I had to go back, re-read the problem, figure out that 3 is allowed, and fix all the conditions. The whole thing took about 20 minutes.

And then came the second part. Oh, the horror!

At first glance, I misunderstood it to mean that one mistake was allowed. I added an error counter, and I thought it was done. It wasn't. Then I realized that whoops: it's not the error that's allowed, it's the omission of a value from the sequence. Ok, that didn't seem complicated either: if the result of the comparison was wrong, I would skip the current value and note that there was a value omitted, so the next time I knew the number sequence was not safe. Of course, I made a bunch of mistakes along the way, and debugged the hell out of it, but it's not so easy to go through 1000 lines, let alone tell by looking at them whether they're right or not.

The realization that the wrong difference of two numbers did not tell me which number to skip came painfully late. By then, an hour had passed, I had at least 5 false attempts, and I was desperate. As it turned out, there were a few dozen cases where the first number had to be omitted, but my solution always kept the first. Great. Even then, I didn't start implementing the obvious solution, in fact, I wanted to avoid, at all costs, recreating the sequences by leaving out one number at a time. I gave up when it came to me: IT WAS ONLY DAY 2, for God's sake.

Looking at other people's solutions yesterday, I was surprised to see that they used this syntax to refer to functions: `intval(...)`. This was completely new to me, I had not known this method before. Up until now, this is how I converted a string of numbers into an array of numbers, using `intval`:
```
array_map('intval', explode(' ', $line));
```
That's the old, the classic method. The new one came with PHP 8.1, and it's called [*The First-class Callable Syntax*.](https://www.php.net/manual/en/functions.first_class_callable_syntax.php) So now I know.

Today's performance would earn me a C-, I guess. Hope to do better tomorrow. :pray:


## [Day 1: Historian Hysteria](https://adventofcode.com/2024/day/1)

Yay, Advent of Code is here! :christmas_tree:

The first day is always easy, I mean it only took about 5 minutes to solve both parts. Then I tinkered with a more sophisticated solution for extracting the two lists of ids from the input, but ultimately found the embedded `array_map` in another `array_map` with necessary `array_column` approach much less clean than the simple 3-line foreach, not to mention the superflous arrays it creates and leaves behind. :shrug:

This year I had this revolutionary idea to put the source code into the `src` directory. I know, right? :open_mouth: It makes running analyzers and linters much easier. Last year I had to adjust the *ECS* config file for each day, and had problems with phpstan messing things up. So, this year I ditched *ECS* for [PHP-CS-Fixer](https://github.com/PHP-CS-Fixer/PHP-CS-Fixer), and updated [phpstan](https://github.com/phpstan/phpstan) to 2.x, so let's see if we can make this work. Both are installed globally via [Composer](https://getcomposer.org/), so you won't find them in `composer.json`. I added two scripts to [composer.json](composer.json), though, to run them easily. So far so good, no errors on day 1. I also considered *phpcs*, but it still doesn't support `PER-CS 2.0`, so maybe next year.

I'm still not convinced that these puzzles require a full object orientedd, enterprise-grade solution with classes, interfaces, testing, autoloading, and all the bells and whistles, but if you're interested in that sort of thing, check out @tbali0524's [repo of Advent of Code solutions](https://github.com/tbali0524/advent-of-code-solutions). Wow, that is so clean you could eat off it! And I should be ashamed of myself.
