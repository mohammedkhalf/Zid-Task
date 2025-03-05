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
>
The getWishlistStatistics method calculates and returns key statistics about wishlist items, including solid Principles and repository Design Pattern with following info :

Total items count: The total number of items in the wishlist.

Average price of an item: The average price across all wishlist items.

Website with the highest total price of its items: The website whose items have the highest combined price.

Total price of items added this month: The sum of prices for items added in the current month.

as we interested with these keys features : 

Efficiency: I used Laravel Eloquent ORM and query builder to perform database operations efficiently.

Modularity: The logic is encapsulated in a single method, making it reusable for both API and CLI.

Readability: The code is clean and easy to understand, with clear variable names and comments.

> **For the refactoring, would you change something else if you had more time?**  
>  
> 

I think Current refactoring in suitable for current business wise and we can build scalable  and Maintainable features for next phases

## Running the project
This project requires a database to run. For the server part, you can use `php artisan serve`
or whatever you're most comfortable with.

You can use the attached DB seeder to get data to work with.

#### Running CLI

The attached test suite can be run using `php artisan wishlist:statistics` command or with param `php artisan wishlist:statistics --statistic={key}`


#### Running tests
The attached test suite can be run using `php artisan test` command.


[laravel]: https://laravel.com/docs/8.x
