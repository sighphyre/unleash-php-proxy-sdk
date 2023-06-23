# Unleash Proxy Client for PHP


## Loose numbers

PHP SDK with 10K toggles in Unleash provides roughly 60 reqs a second.

This SDK with 10K toggles in Unleash provides roughly 200K reqs a second.

Should scale independently of the number of toggles in Unleash.

Performance seems adequate.


## Development

Running the tests

```bash
composer install
./vendor/bin/phpunit tests
```

Running the simple example:
This connects to Edge so you'll need to have that running. Set the following env vars, these are just samples, you'll need to adapt them to your needs:

```bash
export URL=http://localhost:3063/api/ # Edge/Unleash Proxy URL
export API_KEY=*:development.e8dff82b63224ee7e870d5c05f279d8c5f04b7fa40eb6c19bddb11e6 # a frontend token, not a client token
export TOGGLE_NAME=sometoggle # pick a toggle name, for best results it should exist
```

And run the example:

```bash
php examples/simple.php
```

