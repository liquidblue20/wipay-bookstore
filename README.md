## About wipay-bookstore

wipay-bookstore is an API application that strives to communicate under a restful convention. Due to an increase in COVID lockdowns, my store is on the verge of failure. I've been forced to adapt to the trying times and have decided to build out an API for my bookstore. This should increase sales and improve customer convenience and satisfaction.

## Notes
Each time tests are run, the database is refreshed/wiped, rerun the seeds as needed for proper application testing.

Steps to get running after cloning repo:

Composer install

Create env file 

Create API Key entry in ENV file (details in accompanying documentation)

Was created and tested with both sqlite and mysql ,create a database.sqlite file in the database folder if needed

php artisan migrate:refresh --seed

Php artisan key:generate

php artisan test : used to validate some basic functionality of the app

## Requirements

1. Must use PHP and Laravel.
2. Create a database of books with at least Author Name, Title and ISBN
4. Your store must have price and inventory, so for any given book the system should: Set a random price and Set a random number of books in your inventory
5. Anyone can search for a book, and see all the information about the book, including price and inventory.
6. Only registered customers can purchase books.
7. Customers may use WiPay’s Payment API (sandbox credit card) to conduct online
purchases.
8. As the owner of the store, you should be able to perform special actions on your store,such as Viewing any and/or all sales activity and CRUD functionality on any given book.
