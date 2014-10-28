# Redis plugin for CakePHP

[![Build Status](https://travis-ci.org/Oefenweb/cakephp-redis.png?branch=master)](https://travis-ci.org/Oefenweb/cakephp-redis) [![Coverage Status](https://coveralls.io/repos/Oefenweb/cakephp-redis/badge.png)](https://coveralls.io/r/Oefenweb/cakephp-redis) [![Packagist downloads](http://img.shields.io/packagist/dt/Oefenweb/cakephp-redis.svg)](https://packagist.org/packages/oefenweb/cakephp-redis)

Redis (DataSource) Plugin for CakePHP

## Requirements

* CakePHP 2.0 or greater.
* PHP 5.3.10 or greater.
* PhpRedis.

## Installation

### Clone

* Clone/Copy the files in this directory into `app/Plugin/Redis`

### Composer

* Ensure `require` is present in `composer.json`. This will install the plugin into `app/Plugin/Redis`:

```json
{
	"require": {
		"oefenweb/cakephp-redis": "dev-master"
	}
}
```

## Configuration

* Ensure the plugin is loaded in `app/Config/bootstrap.php` by calling:

```php
CakePlugin::load('Redis');
```

* Ensure the plugin is configured in `app/Config/database.php` by specifying:

```php
<?php
class DATABASE_CONFIG {

	public $redis = array(
		'datasource' => 'Redis.RedisSource',
		'host' => '127.0.0.1',
		'port' => 6379,
		'password' => '',
		'database' => 0,
		'timeout' => 0,
		'persistent' => false,
		'unix_socket' => '',
		'prefix' => '',
	);
```

## Usage

Get a (connected / configured) `Redis` instance:

```php
<?php
App::uses('ConnectionManager', 'Model');

$Redis = ConnectionManager::getDataSource('redis');
```

Call Redis's [ping command](http://redis.io/commands/ping):

```php
$Redis->ping();
```
