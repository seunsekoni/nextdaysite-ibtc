### Directions on how to setup this repository

- Clone this repo
- Change directory into the root of the folder of the cloned directory, i.e ``` cd foldername ```
- Create a database in mysql and update the .env details accordingly
- Rename the .env.example file to .env
- Set all your environment variables in the .env file
- Run ``` composer install ```
- Run ``` php artisan key:generate ```
- Run ``` php artisan config:cache ```
- Run ``` php artisan serve ```
- Run ``` php artisan migrate ```
- Run ``` php artisan db:seed ```
- Run ``` php artisan serve ```
- Visit ``` http://localhost:8000 ``` on a browser
- To run tests ``` php artisan test```

- To login, use ``` cordinator@test.com ``` as email and ``` password ``` as password.

**``` App\Https\Controllers\StudentAccountController ``` has a 100% test coverage.**