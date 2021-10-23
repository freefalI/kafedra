First you need to have php 7.3 and Mysql

Clone repository

Run
`cp .env.example .env`

And set database credentials(DB_)

`php artisan key:generate`

edit in .env mysql credentials

Then run database migrations which will create tables and seed data

`php artisan migrate --seed`

Admin \
    login - admin \
    password - admin

User \
    login  - user*( user1/user2/...) \
    password - password
