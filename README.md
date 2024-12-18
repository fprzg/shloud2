# SHLoud2 - Simple Home Cloud

Cloud app made with Laravel 11, Eloquent and Blade.

## Installation
Run `make install` in the same command as Makefile. You'll type your postgresql password so it can create the database and user.

Before installing, check the database configuration at `.env.example`. Make sure the DB_PORT and DB_HOST are the right ones. If you host the database localy, you can check you DB_PORT by runnig `sudo netstat -plnt | grep postgres`. The third field is the host:port. By default it is 127.0.0.1:5432, but if yours is different, change it by modifying the DB_HOST line on .env.port
```[bash]
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=shloud2
DB_USERNAME=shloud2
DB_PASSWORD=pssw0rd
```
