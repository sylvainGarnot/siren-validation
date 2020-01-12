# Siren Validation

This is a Rest Api Symfony Application using FOSRestBundle

## Requirements

- PHP 7.1.3 or higher;
- PDO-SQLite PHP extension enabled;
- and the usual [Symfony application requirements](https://symfony.com/doc/current/reference/requirements.html);

## Installation

```sh
$ git clone git@github.com:sylvainGarnot/siren-validation.git
$ cd siren-validation
$ composer install
```

## Database create & migration

After cloning this repository, edit the .env file and replace the line below with your access to the database
```
DATABASE_URL=mysql://root:@127.0.0.1:3306/siren-validation
```

```sh
$ php bin/console doctrine:database:create
$ php bin/console make:migration
$ php bin/console doctrine:migrations:migrate
```

## Usage

```sh
$ symfony server:start
```

Routes | Methods
--- | ---
siren | @get, @post
siren/{id} | @get, @delete
