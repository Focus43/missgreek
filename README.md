# Concrete5 :: cugreekgods.org #

This is the repository for the cugreekgods.org website. Relevant things for developers:

- Built on Concrete5, runs w/ a basic LAMP stack (and Redis). The number of donation requests can peak to a high number, so we use Redis to alleviate load on the database.

- Everything to run the site is bundled via Docker. Docker is controlled via a few simple commands in a Makefile. If you have GNU make (or Xcode installed) you should be good. In order to get the site running and ready for local development (assuming you're on a \*nix style system - OSX or Linux - all bets are off for you Windows folks):
```
$: git clone [this repo]
$: cd [repo]
$: make dev
```
Then you'll need to make a clone of the database from production (currently on Pagodabox) and import it. To connect from your local machine to the MySQL instance running in Docker:
```
Host: 127.0.0.1 or 0.0.0.0 (or localhost)
User: c5_user
Pass: c5_password
Database: c5_db
Port: 3307
```
These credentials are located in `_docker/.docker.env`.

- If you're done w/ the project and want to free up space for a bit: `make clean` from the project root and it'll remove any containers/images created by `docker-compose`.
