# Hedgebot API client library

This is a library you can use in your PHP projects if you wish to communicate with Hedgebot the Twitch bot.

## Install

Just install the library by requiring it via composer

```
composer require ylorant/hedgebot-api-client
```

## Usage

Here is a simple library usage example. We assume Composer's autoloader has already been loaded.

```php
<?php
use HedgebotApi\Client as HedgebotApiClient;

// The long way to do it
$client = new HedgebotApiClient();
$client->setBaseUrl('http://127.0.0.1:8081');

$endpoint = $client->endpoint('/plugin');
$list = $endpoint->getList();

// A shorter way to do it
$client = new HedgebotApiClient('http://127.0.0.1:8081');
$list = $client->endpoint('/plugin')->getList();
```

Have fun.