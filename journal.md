# My thoughts along the way

## [Day 1: Historian Hysteria](https://adventofcode.com/2024/day/1)

Yay, Advent of Code is here! :christmas_tree:

The first day is always easy, I mean it only took about 5 minutes to solve both parts. Then I tinkered with a more sophisticated solution for extracting the two lists of ids from the input, but ultimately found the embedded `array_map` in another `array_map` with necessary `array_column` approach much less clean than the simple 3-line foreach, not to mention the superflous arrays it creates and leaves behind. :shrug:

This year I had this revolutionary idea to put the source code into the `src` directory. I know, right? :open_mouth: It makes running analyzers and linters much easier. Last year I had to adjust the *ECS* config file for each day, and had problems with phpstan messing things up. So, this year I ditched *ECS* for [PHP-CS-Fixer](https://github.com/PHP-CS-Fixer/PHP-CS-Fixer), and updated [phpstan](https://github.com/phpstan/phpstan) to 2.x, so let's see if we can make this work. Both are installed globally via [Composer](https://getcomposer.org/), so you won't find them in `composer.json`. I added two scripts to [composer.json](composer.json), though, to run them easily. So far so good, no errors on day 1. I also considered *phpcs*, but it still doesn't support `PER-CS 2.0`, so maybe next year.

I'm still not convinced that these puzzles require a full object orientedd, enterprise-grade solution with classes, interfaces, testing, autoloading, and all the bells and whistles, but if you're interested in that sort of thing, check out @tbali0524's [repo of Advent of Code solutions](https://github.com/tbali0524/advent-of-code-solutions). Wow, that is so clean you could eat off it! And I should be ashamed of myself.
