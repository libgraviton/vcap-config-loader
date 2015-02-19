# VCAP_SERVICES loader for php

[![Build Status](https://travis-ci.org/libgraviton/vcap-config-loader.svg?branch=develop)](https://travis-ci.org/libgraviton/vcap-config-loader) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/libgraviton/vcap-config-loader/badges/quality-score.png?b=develop)](https://scrutinizer-ci.com/g/libgraviton/vcap-config-loader/?branch=develop) [![Code Coverage](https://scrutinizer-ci.com/g/libgraviton/vcap-config-loader/badges/coverage.png?b=develop)](https://scrutinizer-ci.com/g/libgraviton/vcap-config-loader/?branch=develop) [![Latest Stable Version](https://poser.pugx.org/graviton/vcap-config-loader/v/stable.svg)](https://packagist.org/packages/graviton/vcap-config-loader) [![Total Downloads](https://poser.pugx.org/graviton/vcap-config-loader/downloads.svg)](https://packagist.org/packages/graviton/vcap-config-loader) [![Latest Unstable Version](https://poser.pugx.org/graviton/vcap-config-loader/v/unstable.svg)](https://packagist.org/packages/graviton/vcap-config-loader) [![License](https://poser.pugx.org/graviton/vcap-config-loader/license.svg)](https://packagist.org/packages/graviton/vcap-config-loader)

Parses and loads the contents of a vcap services variable as provided by
cloundfoundry clouds.

This is just a very small wrapper around peekmo/jsonpath. It is intended
to make it very easy to configure services based on things cloudfoundry
injects through the VCAP_SERVICES env variable.

## Install

```bash
composer require graviton/vcap-config-loader '*'
```

## Usage

```php
<?php

require 'vendor/autoload.php';

use Graviton\Vcap\Loader;

// create loader and inject data
$loader = new Loader;
$loader->setInput($_ENV['VCAP_SERVICES']);

// what to extract
$type = 'mariadb-';
$name = 'my-awesome-service';

// data extraction
$dbConfig = array(
    'host' => $loader->getHost($type, $name),
    'port' => $loader->getPort($type, $name),
    'database' => $loader->getDatabase($type, $name),
    'username' => $loader->getUsername($type, $name),
    'password' => $loader->getPassword($type, $name),
);
```
