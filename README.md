# The effective succotash

Effective succotash, apart from the dish, is a proof of concept of a payment system written in PHP, and then in Typescript, and now in Kotlin.

Some principles of Robert C. Martin's Clean Architecture book where used in the development, the structure and interactions where created using its concepts.

`Yes, this is my pet project, and I rewrite it all the time.`

### How to run

There is a Dockerfile and docker-compose.yml, so just clone the repo and execute the command:
```
$ docker-compose up -d
```

And you're good to go!

### Endpoint for creating a transaction
```
  POST - /transactions
```

### Payload for creating the transaction
```json
{
  "payer": "2a58a6be-f4c3-4b3d-bf09-9765b583cf10",
  "payee": "fae16f31-eff8-4c65-bed2-052d432aac27",
  "value": 10.00
}
```

### Missing and good to have features
- [ ] All `/transactions` methods.
- [ ] A `/balances` route, to get the user's current balance.
- [ ] A `/deposits` route.
- [ ] A `/withdraws` route.
