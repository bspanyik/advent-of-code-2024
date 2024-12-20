# My thoughts along the way

## [Day 15: Warehouse Woes](https://adventofcode.com/2024/day/15) - Sokoban
Sometimes you get lucky. Sometimes you instinctively come up with a code that is small, efficient, and cleverly avoids the traps built into the task. Like pushing two double-wide crates with an empty space between them against two sides of a wall:
```
########
...#....
.[].[]..
..[][]..
...[]...
...@....
```
I don't know if this really happened in the second part, because I simply didn't think of such strange edge cases. I was later talking to my dad about part 2 and he mentioned that he was struggling with pushing weird shapes of multiple crates and holes between them vertically, and I was like, wow, I hadn't even thought of that. :sweat_smile: Somehow I managed to write an algorithm for vertical crate-pushing on the first try (or rather, with a quick simplification, on the second try) that got through all the built-in traps. Yeah, I was surprised too.

By the way, **Sokoban** (or [Soko-Ban](https://en.wikipedia.org/wiki/Soko-Ban)) was a logic puzzle game on ancient PCs in the late 80s. As far as I can remember I played both the Commodore 64 and the early PC AT version (this was the pre-286 era of Intel CPUs) with CGA graphics. In this game you were a warehouse worker in a maze-like warehouse with lots of crates, and you had to push them to their designated places. See its [Wikipedia page](https://en.wikipedia.org/wiki/Sokoban) for more information.

![Screenshot of the PC version of Soko-Ban with CGA graphics](src/day15/sokoban.jpg)

## [Day 14: Restroom Redoubt](https://adventofcode.com/2024/day/14) - Destroy him, my robots! Or make a Xmas tree instead?

So many things to talk about! :blush:

The day before had taught us to use maths instead of jumping into a loop of crazy and mostly unnecessary calculations and operations, right? I knew I could calculate where the robots would end up after 100 seconds, regardless of their neat trick of teleporting (as in wrapping around the room). I just had to use *modulo*.

Now here's the problem: modulo sometimes doesn't work the way you expect it to. If you open Calculator in Windows, set it to scientific mode, and type `-2 mod 7`, you'll get `5`. That would be perfect. If we have a room that is `11` blocks wide and `7` blocks high, and there's robot at the position of `4,1` that's going diagonally at the speed of `2, -3` blocks per second, it's going to end up at block `6, -2` in the next second. `-2` means it reaches the top wall and teleports to the other end of the room, then moves 2 blocks up, so it would actually end up at `6, 5`. And this is exactly what `-2 mod 7` predicted. (Obviously, we count the blocks in the room from zero.) We know its starting position, we know its speed, so we know exactly where it's going to be! Great!

Then let's open PHP's interactive shell in the command line, and try this:
```
$ php -a
Interactive shell

php > echo -2 % 7;
-2
```
Uh, what? There's an [excellent article on this in Wikipedia](https://en.wikipedia.org/wiki/Modulo). The actual implementation depends on the programming language you are using. C-based languages, such as PHP, define it like this:
```
mod(a, n) = a - n * intdiv(a / n)
```
while Python follows *Donald Knuth*'s definition:
```
mod(a, n) = a - n * floor(a / n)
```
The difference is in the treatment of the original division. In PHP, it's truncated to the nearest integer: `-2 / 7` is `-0.28571428571429`, so truncated it becomes `0`. On the other hand, `floor(-2 / 7)` is `-1`!

So what can we do? [This excellent article from 2011](https://torstencurdt.com/tech/posts/modulo-of-negative-numbers/) suggests using the ternary operator or applying the modulo twice. I prefer the latter, or at least this is what I came up with when calculated the position of the robots:
```
$x = (($rx + $vx * STEPS) % WIDTH + WIDTH) % WIDTH;
```
Now, about the second part. Probably the shortest problem definition ever. :rofl: At first, like many others, I was annoyed. Robots arranging themselves into a picture of a Christmas tree can be a lot of different things. What Christmas tree? What does it look like? A sample image would have been useful.

To be honest, I had no idea what to do, so I went to [reddit for inspiration](https://www.reddit.com/r/adventofcode/). I found dozens of memes that made me laugh, especially the one about how the lesson of the previous days was about thinking ahead and using maths instead of iterating over steps, while this time the second part was all about iterating until God knows when. :stuck_out_tongue_closed_eyes: That really resonated with me deeply. I also found memes about generating thousands of images to find the one with the Christmas tree, animations showing how the robots got to this wonderful state, ideas about what the quadrants meant in part one and how they could help to solve part two, so I've decided to implement most of them, just to practice and learn something new.

The beautiful idea of the robots all being in different locations while participating in the Christmas tree image came from [Neil Thistlethwaite's video on Youtube](https://www.youtube.com/watch?v=U3SoVMGpF-E). Just imagine how you would create the input file for such a task! You'd work backwards, of course: draw the majestic Christmas tree with a few hundred "robots" first, then randomly add a few more until you get to 500. Add random velocity to each, then calculate where they would all end up (or start from) in a gazillion thousands of steps, back in time. There you go! So, when the number of the robots in the input file equals the number of the discrete locations of all those robots, that's probably where the tree happens. This is `day14-2.php`. I also created a variant (`day14-2a.php`) that echoes out the image to the console.

Next, I wanted to learn how to create images with PHP. So I installed the `gd` extension, then after googling around for good examples I ended up with `day14-2b.php` using `imagecreate`, `imagecolorallocate`, `imagesetpixel`, and `imagepng`. It wasn't too difficult, although `phpstan` annoyed the hell out of me by saying that the `$color` parameter of `imagesetpixel`, which came directly from `imagecolorallocate`, might not be safe to use. So I forced it to be an integer on line 31. In a surprisingly few seconds I had a folder with 10K images. Here's what it looked like in File Explorer:

![Screenshot of File Explorer with thumbnails of the generated images, including the one with the Christmas tree](src/day14/xmastree-cropped.png)

Finally, I wanted to do something with the quadrants and the safety factor from part one (`day14-2c.php`). This idea also came from reddit, but I wrote it hours later and I couldn't remember if I should calculate the highest or the lowest safety factor I had to go for. I went for the highest first, and it turned out to be the lowest.

All in all, a very fun problem with memorable lessons. :christmas_tree:


## [Day 13: Claw Contraption](https://adventofcode.com/2024/day/13) - Elementary school maths, my dear Watson

Can you recognise a system of linear equations at a glance? Because if you can, and you solved the first part that way, *the right way*, you were done: it would have worked for the second part too.

Or did you, like me, go on a brute-force rampage without ever thinking: the *cheapest* way, you say? Is there even more than one way? Linear systems of equations have the peculiar way of *usually* (i.e. always) having *one* solution for each unknown. But who cares, right? Let's run a loop to a hundred, multiply, subtract, divide, check for remainders, store the result in an array! Don't forget to sort that array, regardless of having only one item in it, to make sure you put the lowest of items (of the one) first, then sit back and enjoy a good result. Oh, the smell of *being so smart* early in the morning! :satisfied:

Ok, now add 10000000000000 to the x and y of the prize. That's 13 zeros if I'm counting correctly. Huh? Brute forcing won't do anymore. Better  start thinking like any 13-year-old elementary schoolboy in a maths class:
```
Button A: X+94, Y+34
Button B: X+22, Y+67
Prize: X=8400, Y=5400

94a + 22b = 8400
34a + 67b = 5400

34a = 5400 - 67b --> a = (5400 - 67b) / 34

       (5400 - 67b)
94 * ---------------- + 22b = 8400
             34

94 * 5400 - 94 * 67b + 34 * 22b = 34 * 8400 --> 507600 - 6298b + 748b = 285600

6298b - 748b = 507600 - 285600 --> 5550b = 222000 --> b = 40
```
...and so on. All you need to do is translate this correctly into your 6 different but very similarly named variables, all with `a` and `b` and `x` and `y`, and `prize`. 30 minutes of continuous swearing. Here's what you get if you've done it right:
```
     (aX * prizeY - aY * prizeX)
b = ------------------------------
         aX * bY - aY * $bX
```
This `b` and then, later, `a` must be an integer, and you're done. Only 6-7 lines of readable code, no loops, just pure maths. What is the name of that smell, I wonder. :smirk:


## [Day 12: Garden Groups](https://adventofcode.com/2024/day/12) - All sides of the story

There is this phenomenon, where you go test by test, always fixing something in the code, until all the tests pass. Then you run it on your real input and it fails. This day we had lots of test cases, not just 1 or 2 as usual, but the input file was huge, with hundreds of garden plots, and I had no idea how to debug it efficiently. Luckily, I didn't even have time to think about it too much.


I came back to it a day later (after solving the fairly easy Day 13 problems in the morning, but more on that later). My idea, after much deliberation, was to find neighbouring blocks around the plots, and separate them by checking whether the boundary between them and the plot blocks is horizontal or vertical. Then I went through the data collected, and tried to draw conclusions about the sides or edges of the plots. If two boundary blocks were next to each other, that counted as one side. Inner boundary blocks, aka *holes* were counted as 2-2 sides. In the end it worked perfectly on all test cases, but not on the real garden, my input.

So I decided to "cheat". I downloaded someone else's solution, and while trying to see as little of the logic as possible, I edited it to produce statistics about the real sides of the plots, and then I compared them to mine. I quickly found differences, where my solution was missing ONE edge somewhere. I copied a test plot, a 186-block area with completely irregular shape, from the input file into Google Sheets, and started to work out the horizontal and vertical edges by hand, comparing them with what my code found. And there it was, staring back at me, the flaw in my algorithm: two consecutive vertical boundary blocks (see the exclamation marks in the image below) with two different edges. The top on has and edge on the right, while the bottom on the left. So they were not part of *one side*, but two.
```
AAAAA
A.!AA
AA!..
```
It wasn't enough to separate them horizontally and vertically. They had to be separated by discrete directions or positions: top, bottom, left, right. Surprisingly, this made the code shorter and the logic less complicated.


## [Day 11: Plutonian Pebbles](https://adventofcode.com/2024/day/11) - Defeat on Day 11

Well, when I said that permutations were my kryptonite, I meant one of them. Number theory, or whatever it is today, is definitely another one.

A few years ago there was a day when monkeys took things out of our backpack and threw them around in absolute chaos, and we had to work out where our stuff would end up by adding, multiplying, and occasionally dividing their numbers. In the second part the result grew exponentially and I could never solve it. I remember the defeat. I know it was some trick with modulo arithmetic, or something like that, but to this day I still don't know what the solution was.

Today's part two feels very similar. I've spent hours, written hundreds of lines of code (then deleted them), made hundreds of notes in Notepad while calculating my ass off, and I still don't feel any closer to the solution. On the other hand, I feel angry, miserable, and mostly defeated. I've calculated the number of unique numbers that could appear on the stones during this ordeal, and it's surprisingly low, around 3500. It must be something to do with caching, perhaps, but couldn't find out how. I have a 5 in my input -- I have literally hours of work in hundreds lines of notes, tables, and calculations of how it changes in each step, what's remaining, what should be stacked, what could be cached. I've noticed repeating patterns, I can figure out values and stack contents 51 steps ahead, and there's still no use in any of it. Quite frustrating.

So I'm going to put this away now and let it rest. I haven't given up on it yet, but I need something else to think about before I come back to it. Again. Later. If ever. :disappointed:


## [Day 10: Hoof It](https://adventofcode.com/2024/day/10) - Path-finding with a happy accident

Nothing to be proud of today, just a quick and dirty hack, because I've got to run. Busy day. Two years ago there was a very similar path-finding problem where each step could have a maximum value difference of 2. I used the same method here, looking in the four directions for the next steps, collecting them, and then going on from there.

The funny thing is that I accidentally solved part two instead of part one first, and had to filter out results with the same peaks. So when I saw what part two was about, with a smile from ear to ear, I just pressed undo 4 times in the editor. And there it was. It probably means that my part one is awfully inefficient, but *meh*... :stuck_out_tongue_winking_eye:


## [Day 9: Disk Fragmenter](https://adventofcode.com/2024/day/9) - Remember Norton SpeedDisk? Yes, I'm *that* old!

I don't know if I was still too sleepy in the morning, or what, but took an enormous amount of time to figure out how the disk compression worked. That paragraph about the `12345`, which was supposed to clean everything up, was a total mess after reading it once, twice, or three times. When I finally got it, my first reaction was to check how long the input was, because I was afraid of bulding the whole disk up in the memory, uncompressed. Of course it was huge. So I tried to figure out a way to do the calculations on the fly, and to my surprise, I put it together quite quickly.

This is how it works: we start at the beginning of the input array. At every even position in the array (`$i %2 == 0`) there's a file, so we do the necessary multiplications and add them to the sum. At each odd position, though, there's free space, which should be filled up from the back. We know that the total length of the array is odd, so the last array item is a file. We're gonna keep it that way. We cut the last file value from the array (`array_pop($input)`), and later the free space value before it, take note of the file id, and build a stack from of it (`array_fill(0, $last, $endId)`). Then we use this stack to fill up the empty space, as in calculating the multiplications and adding them to the sum. If there's still some empty space left, we cut the last file value again, build the stack, fill the gap, and move on. Brilliant, eh? :grin:

Two things to watch out for: one is that we're eating up our array from the back, so in the head of our `while` cycle we'd better check if our item pointer is still in the array. Second: when we come out of the `while`, our stack may not be empty. The file ids still in there are waiting to be calculated and added to the sum.

Reading the second part, I know I won't be able to solve it without building the complete, uncompressed disk, so I checked the value of the `$pos` variable from part 1, and it was over 50K (I expected worse), so I knew it would fit comfortably in the memory we had. I separated files and free space along the way, and worked backwards from there.

The big bad catch here was that when we look for free space for our file in the back, we have to make sure we're not looking *behind* the file! This seems obvious, but I missed it, and I couldn't figure it out on my own for a long time, mainly because it didn't happen with the test data in the problem text. :worried: Needed a bit of help to realize where I went wrong. We also needed to clean up in the original location of the moved file. I did this with a nice trick using `array_replace` which is not a commonly used array function around here.

The second solution runs for over a second, and looking at it, there's certainly room for improvement, but I'd rather save my energy for tomorrow. :wink:


## [Day 8: Resonant Collinearity](https://adventofcode.com/2024/day/8) - I draw the line here

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
