## help: shows this message.
.PHONY: help
help: 
	@echo 'Usage'
	@sed -n 's/^##//p' ${MAKEFILE_LIST} | column -t -s ':' | sed -e 's/^/ /'


## confirm: asks the user for confirmation.
.PHONY: confirm
confirm: 
	@echo -n 'Are you sure? [y/N] ' && read ans && [ $${ans:-N} = y ]


## install: Setup database and app.
.PHONY: install
install: db/setup app/setup
	@php artisan migrate
	@echo 'App installed successfully'


## run: Run the app.
.PHONY: run
run: 
	php artisan serve

## app/setup: Setup php and laravel.
.PHONY: app/setup
app/setup: 
	composer install
	cp .env.example .env
	php artisan key:generate


## db/setup: Setup the database and user.
.PHONY: db/setup
db/setup: 
	@echo "Connecting to PostgreSQL and setting up user and database..."

	@echo "Creating database 'shloud2'"
	psql -U postgres -c "CREATE DATABASE shloud2;"

	@echo "Creating user 'shloud2'"
	psql -U postgres -c "CREATE USER shloud2 WITH PASSWORD 'pssw0rd';"
	psql -U postgres -d shloud -c "GRANT USAGE, CREATE, ALTER ON DATABASE shloud2 TO shloud2;"

	@echo "Database setup completed successfully."


## db/clean: Drop the database and user.
.PHONY: db/clean
db/clean: 
	@echo 'Cleaning up database...'
	psql -U postgres -c "DROP DATABASE IF EXISTS shloud2;"
	psql -U postgres -c "DROP USER IF EXISTS shloud2;"
	@echo "Cleanup completed."


## cleanup: Cleans all the files of the app.
.PHONY: cleanup
cleanup: db/clean
	@echo 'Cleanup completed.'

