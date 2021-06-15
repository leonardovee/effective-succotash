# The effective succotash

[![Build Status](https://www.travis-ci.com/leonardovee/effective-succotash.svg?branch=main)](https://www.travis-ci.com/leonardovee/effective-succotash) [![Maintainability](https://api.codeclimate.com/v1/badges/92cd4b4959a2085b2e24/maintainability)](https://codeclimate.com/github/leonardovee/effective-succotash/maintainability) [![Test Coverage](https://api.codeclimate.com/v1/badges/92cd4b4959a2085b2e24/test_coverage)](https://codeclimate.com/github/leonardovee/effective-succotash/test_coverage)

Effective succotash, apart from the dish, is a proof of concept of a payment system written in PHP.

Some principles of Robert C. Martin's Clean Architecture book where used in the development, the structure was created using the [Lumen framework](https://lumen.laravel.com/docs), BUT principles are still there, and the domain is decoupled from the rest of the Lumen source code.

### How to run

There is a [docker image](https://lumen.laravel.com/docs) built for the app, just clone the repo and execute the command:
```
$ cp .env.example .env
$ docker-compose up -d
```

After building up the containers you'll have to run the migrations for the database.
```
$ docker exec -it <CONTAINER> sh
$ php artisan migrate
```

And you're good to go!

### Endpoint for creating a transaction
```
  POST - /transaction
```

### Payload for creating the transaction
```json
{
    "amount" : 100.00,
    "payer" : 1,
    "payee" : 2
}
```

There is also a endpoint for creating user's of the transactions on `/user`.

### Missing and good to have features
- [ ] `/transaction` methods.
- [ ] `/balance` route.
- [ ] `/deposit` route.
- [ ] `/withdraw` route.

### Security Vulnerabilities

If you discover a security vulnerability within this project, please open an issue. All security vulnerabilities will be promptly addressed.

