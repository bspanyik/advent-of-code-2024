# My thoughts along the way

## [Day 8: Resonant Collinearity](https://adventofcode.com/2024/day/8) - Draw a line

The real challenge with Day 8's problem was understanding what it was all about. This time the description was a bit over the top with frequencies, antennas and antinodes.

Well, the problem, actually, was to take two points on a plane, create a segment between them, and then draw the same segment from both points in opposite directions. Where their ends were, there were the so called antinodes. The only difficulty was that some antinodes were at the same x,y coordinates, so it wasn't enough to simply calculate the number of them, we had to filter out duplicates. In the second part we had to repeat the same thing until we went off the map.

Easy like Sunday morning. :wink:

## [Day 7: Bridge Repair](https://adventofcode.com/2024/day/7) - Operation: Permutation

Please don't look at today's code. It's ugly as hell. I hate code combinations and permutations, etc., and it shows. This is my kryptonite.

Also my solution creates all repeating permutations of all operands, regardless of whether even the very first one solves the equation. At least I implemented a cache for already generated permutations for the second part, so the next time the same number of... um... numbers comes up, I just pull all possible operands out of the cache. This makes the second part run in less than 3 seconds.

---
If you want to see a truly wonderful solution to this problem, take a look at [**vuryss's Day 7**](https://github.com/vuryss/aoc-php/blob/master/src/Event/Year2024/Day7.php). What an absolute beauty of simplicity and finesse! :heart_eyes: I'm in love with that code. Oh, how I wish I could think like him!

---
Unfortunatelly, I had to create a baseline file for phpstan because there was no way I could annotate the params for my butt ugly `permute` function that would satisfy phpstan. I don't really understand its problem. For this line where I store another completed permutation:
```
            $perms[] = $carry;
```
it says: `Parameter &$perms by-ref type of function permute() expects array<int, array<string>>, array given.`

Yes, and? I mean we know what `$carry` is, I annotated it to be `string[]` or as phpstan likes to say it: `array<string>`. So why is array given? And what's the problem with that? Even if it were empty (which it is not), an empty `string[]` would itself be an `array`, wouldn't it?

I hate it when messing with a linter's cryptic error messages takes longer than actually coding the solution to the problem, so I removed the annotations, and generated a baseline file, while disliking myself and phpstan equally. :shrug:


## [Day 6: Guard Gallivant](https://adventofcode.com/2024/day/6) Revisited: The Tale of Guard Who Was Too Efficient

UPDATE: My friend pointed out that turning & moving in one step wasn't *efficient*, it was plain stupid. What if there was an object right after the turn? The guard would have stepped on it, completely incorrectly. I was very lucky that there was no such turn on the map. Or, rather, unlucky, since it would have pointed out my mistake much sooner. :shrug:

---
So, here's the story why my loop-detection failed. This is my original guard-walk algorithm:
```
        $ny = $gy + $dy;
        $nx = $gx + $dx;
        if (!isset($map[$ny][$nx])) {
            return false;
        }

        if ($map[$ny][$nx] !== '#') {
            $gy = $ny;
            $gx = $nx;
        } else {
            if ($dx !== 0) {
                $dy = $dx;
                $dx = 0;
            } else {
                $dx = -1 * $dy;
                $dy = 0;
            }
            $gy += $dy;
            $gx += $dx;
        }
```
- `$gx`, `$gy` is the current x,y position of the guard,
- `$dx`, `$dy` is the current direction the guard is going

This is how it works:

1. We calculate where the guard would be after her next step, that's `$nx`, `$ny`.

2. If these are pointing out of the map, the guard would leave, so no loop, goodbye.

3. If not, then if she's not blocked by an object (`#`), then she goes there.

4. Otherwise, she turns.

    Turning is quite simple: if she was moving horizontally (`$dx !== 0`), then she'll be moving vertically from now on, pretty much the same way, as before. Otherwise, she'll move horizontally, but in the opposite of her previous vertical direction. (`$dx = -1 * $dy`)

5. And after the turn, she moves in her new direction.
```
            $gy += $dy;
            $gx += $dx;
```
And that's the problem. She shouldn't. She should *either move OR turn*, and not *turn & move* in one step. It made her quite efficient in part 1, and even at the beginning in part 2 when we were calculating her original path. But while we're **looking for a loop**, i.e. registering where she is and which direction she's moving right after each step, we're missing her position after the turn.

I'll show you. This is her right before the turn: `#<`. We saved this location and direction. And this is her after the turn & move:
```
.^
#.
```
Now we save her new location and direction. And we have just missed this: `#^`. So, next time she walks up by that object, we will **not recognize** that she's already in a loop.

That's it. I commented out that two lines, and got right result. :blush:


## [Day 6: Guard Gallivant](https://adventofcode.com/2024/day/6) - Can't-a-loop?

One day behind, it is time to admit my first failure. :worried: It looks like day 6 is last year's day 5. I'm stuck now pretty much the same I was stuck then: being so sure of myself (and my code) that I couldn't understand why it didn't produce the right result. I'm totally out of ideas.

Part 1 was obvious and came out sort of automatically, without much thought.

Now my first approach of part 2 was totally wrong: I was trying to block the guard at every step and checked if she, redirected, stepped on a cell she had already been on. This failed even on the test map, because it didn't count that a loop can have steps that were not part of the original path.

So my second approach was to keep on moving from the redirection until I found an already passed step -- this didn't work either.

Back to the drawing board, I came up with the obvious idea that probably everyone else had: get the guard's path first, then modify the map one block at a time along her path, and run her along from the beginning, as if it were *the map* she has to patrol. And it came back with 1864 blocks. Which is too few, AoC said.

Well then, let's brute force it. Start with the top left cell, put a new block there if it's empty, let the guard run along, see if she escapes, and if not, it's a valid block. It's going to take so much more time, checking so many cells completely unnecessarily, but in the end it has to come up with the right result, right? Yeah, it came up with... drum roll, please... **1864 blocks**. :confounded:

And that's where I am now.

I have no idea what's wrong with my loop detection. What's a loop? (*Baby, don't hurt me!*) A loop is when the guard steps on the same cell, going in the same direction, where she had already been. Am I not right?! Well, obviously, not. :unamused:


## [Day 5: Print Queue](https://adventofcode.com/2024/day/5) - Under-engineering

I was a little late to the party this morning because I slept in, so I only had time to read today's first part, and then I had to get on with my duties. My initial idea was to use the rules to determine the sorting order of the pages and then do something with that, but first, it seemed like a complicated process, and second, PHP is not very good at handling arrays as sets.

So, the next idea was, and I later coded this to be my first solution, to prepare a `$pageRules` array that contained each page as an array key, and two arrays as items: one for all the pages that come before that key page, and another for all that come after. Then I iterated over the updates, and used a neat trick to handle the current list of pages by shifting them left one by one with `array_shift`. The current page was the one that had just been shifted out from the array. Any previous pages that had been shifted out before were treated as "before", and any pages that were still in the array were treated as "after". All I had to do was to `array_diff` them with the `$pageRules` before/after arrays that belonged to the current page. Worked surprisingly well, though it felt a bit too complex.

I looked around how others solved it, and found a solution that was so much simpler than mine: it flipped the pages of the current update to use their array keys as indexes, then iterated over the rules array, checking the indexes for each pair of pages: the left one had to be smaller. I admired the idea, even more the simplicity, but I didn't like iterating over the whole rules array, since it contained hundreds of items that were irrelevant to the pages being examined. So I refactored my `$pageRules` array: it still used all pages as index keys, but they only contained the array of pages that came after them. I iterated over the updates, flipped the current list of pages for indexes, then iterated over the pages, then iterated over the rules that belonged to that particular page. It worked, still felt a bit unnecessarily complicated, but I was happy with it.

Correcting the incorrectly sorted updates in the second part turned out to be much easier than the first part. Again. What is wrong with Advent of Code this year? :thinking: I had to separate and flip the rules, so that they could easily be used in an `isset`, iterate over the updates, create a correctly sorted version of the pages using `usort` with the rules, compare it to the original, count the middle ones if necessary, and it was done.

And then I realized: OMG, it would have worked just as well for the first part! :facepalm: :sweat_smile:

---
Also #1, I stopped using PHP_EOL today, and I'm this close to defining the linefeed character as a new constant, LN. Still considering it.

Also #2, I do not agree with the [mandatory trailing comma rule when splitting function parameters into multiple lines, as per PER-CS 2.0](https://www.php-fig.org/per/coding-style/#26-trailing-commas). It makes sense with multiline arrays, it doesn't in function calls, I think.


## [Day 4: Ceres Search](https://adventofcode.com/2024/day/4) - Day of the SAMX.

It was pretty obvious from the start that I could avoid looking "back" (in 4 extra directions) by checking the word not only for XMAS but also for SAMX. Coding took about 10 minutes, debugging another 30 (AGAIN!) because of a missing equal sign in a *less than or equal* comparison. :facepalm: Then came the second part and the surprise of the day with it, because it was *waaaaaay* easier than the first. :astonished: I mean I couldn't believe when I had the correct result after about 3 minutes of typing.

Chill.

I'm just a bit worried that this might be a sign of something terrible to come tomorrow. Last year day 5 was the first tough nut to crack, at least for me. I was so sure my method was correct that it took me days to realize my mistake and finally solve it. Hopefully I'll have better luck this time!


## [Day 3: Mull It Over](https://adventofcode.com/2024/day/3) - Darn regular expressions!

I hate regular expressions. Don't know why.

Every time I know I have to deal with regular expressions, I also know it's not going to be smooth sailing. This time I knew I had to use `preg_match_all`, I just didn't have the slightest idea how it worked. So [I looked it up in the PHP docs page](https://www.php.net/manual/en/function.preg-match-all.php), and from the examples there I came to the conclusion that I could not only extract the whole `mul(x,y)` expression, but `x` and `y` as well. Easy peasy, lemon squeezie.

For the second part I first tried to `preg_split` the text on `don't()+anything+do()`, then `implode` the rest and run part 1 on it, but it didn't work. Don't know why. I think it should have, so I might revisit this if I'll have time. But another example on the `preg_match_all` page came to the rescue: you can match several things at the same time, separated by the `pipe` character. Great. I just had to `var_dump` the results, to know how to handle it, and I was almost done, right?

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
