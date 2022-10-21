## Backend Assignment

## Task

You were given a sample [Laravel][laravel] project which implements sort of a personal wishlist
where user can add their wanted products with some basic information (price, link etc.) and
view the list.

#### Refactoring

The `ItemController` is messy. Please use your best judgement to improve the code. Your task
is to identify the imperfect areas and improve them whilst keeping the backwards compatibility.

#### New feature

Please modify the project to add statistics for the wishlist items. Statistics should include:

- total items count
- average price of an item
- the website with the highest total price of its items
- total price of items added this month

The statistics should be exposed using an API endpoint. **Moreover**, user should be able to
display the statistics using a CLI command.

Please also include a way for the command to display a single information from the statistics,
for example just the average price. You can add a command parameter/option to specify which
statistic should be displayed.

## Open questions

Please write your answers to following questions.

> **Please briefly explain your implementation of the new feature**
>
> - I created a single action controller for statistics to separate logic
>
> - I created a servic (StatisticsService) to contain statistics logic to not clutter controller
>
> - I used Laravel's Query Builder to use aggregate functions (sum, avg, count...) to get better results and write fewer lines of code
>
> - I seperated each needed statistic into a separate function because a function is only supposed to perform one goal
>
> - I made only 2 functions public to: a) get access to all statistics b) get only one statistic
>
> - To get website with highest total price I used regular expresstion to extract the domain then group by it and use collection methods to map it and get the desired result
>
> - I used array_search to find the website name by it's value because the array keys did not match
>
> - In getOneStat function I used match in favor of switch because I needed strict equality and to evaluate one value which made it the right tool for the job
>
> - When implementing the command I made sure to write descriptions which can be viewed using: php artisan help items:stats
>
> - To get all statistic via command line use: php artisan items:stats
>
> - To get a specific statistic use the --stat flag: php artisan items:stats --stat
>   _..._

> **For the refactoring, would you change something else if you had more time?**  
>  No, I believe I refactored it to my best knowledge
> _..._

## Running the project

This project requires a database to run. For the server part, you can use `php artisan serve`
or whatever you're most comfortable with.

You can use the attached DB seeder to get data to work with.

#### Running tests

The attached test suite can be run using `php artisan test` command.

[laravel]: https://laravel.com/docs/8.x
