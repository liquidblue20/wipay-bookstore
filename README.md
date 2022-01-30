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