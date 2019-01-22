# Redis plugin for CakePHP

[![Build Status](https://travis-ci.org/Oefenweb/cakephp-redis.png?branch=master)](https://travis-ci.org/Oefenweb/cakephp-redis)
[![PHP 7 ready](http://php7ready.timesplinter.ch/Oefenweb/cakephp-redis/badge.svg)](https://travis-ci.org/Oefenweb/cakephp-redis)
[![Coverage Status](https://codecov.io/gh/Oefenweb/cakephp-redis/branch/master/graph/badge.svg)](https://codecov.io/gh/Oefenweb/cakephp-redis)
[![Packagist downloads](http://img.shields.io/packagist/dt/Oefenweb/cakephp-redis.svg)](https://packagist.org/packages/oefenweb/cakephp-redis)
[![Code Climate](https://codeclimate.com/github/Oefenweb/cakephp-redis/badges/gpa.svg)](https://codeclimate.com/github/Oefenweb/cakephp-redis)

Redis (DataSource) Plugin for CakePHP

## Requirements

* CakePHP 2.9.0 or greater.
* PHP 7.0.0 or greater.
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

	public $redis = [
		'datasource' => 'Redis.RedisSource',
		'host' => '127.0.0.1',
		'port' => 6379,
		'password' => '',
		'database' => 0,
		'timeout' => 0,
		'persistent' => false,
		'unix_socket' => '',
		'prefix' => '',
	];
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
