# Unleash Proxy Client for PHP

## Development

Running the tests

```bash
composer install
./vendor/bin/phpunit tests
```

Running the simple example:
This connects to Edge so you'll need to have that running. Set the following env vars, these are just samples, you'll need to adapt them to your needs:

```bash
export URL=http://localhost:3063/api/
export API_KEY=*:development.e8dff82b63224ee7e870d5c05f279d8c5f04b7fa40eb6c19bddb11e6
export TOGGLE_NAME=sometoggle
```

And run the example:

```bash
php examples/simple.php
```
