# VCAP_SERVICES loader for php

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
