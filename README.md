# Luxstack Razrbit SDK for PHP (Beta)

This package contains the Luxstack Razrbit Bitcoin platform - allowing you to build, test and scale bitcoin apps faster.

I did not create the Razrbit Bitcoin Platform, it is built by Luxstack. I have simply written some automated tests for it and added some extra Laravel support.

## Installation

Begin by installing this package through Composer.

```js
{
    "require": {
		"freddiecoleman/luxstack-razrbit": "1.1.0"
	}
}
```

## API Key

In order to use this package you will need to set the following constants containing your Luxstack App ID and secret.

```php

CONST MY_APP_ID     = "A25AOpLUoT";
CONST MY_APP_SECRET = "688e2b77-09a3-4945-9468-bf188ff3de88";
```

## Laravel Users

If you are a Laravel user there is a service provider that you can make use of.

```php

// app/config/app.php

'providers' => [
    '...',
    'Luxstack\Razrbit\RazrbitServiceProvider'
];
```

When this provider is booted, you'll have access to a helpful `Razrbit` facade, which you may use in your controllers.

```php
Route::get('/', function()
{
	return Razrbit::marketsPrice('USD');
});
```

## Non-Laravel users

If you are not using Laravel you will need to instantiate Razrbit manually yourself.

```
$razrbit = new Razrbit(MY_APP_ID,MY_APP_SECRET);
```

Once this has been done, any available services can be invoked. Response data is accessible in the callback and can be used as needed:

```
$new_address = $razrbit->walletCreateNewAddress();

$balance = $razrbit->getBalanceFromAddress($new_address);
```

## API

### Wallet 

```
$razrbit->walletCreateNewAddress();
```
Creates a new bitcoin address

```
razrbit->walletSendAmount("1exampleFromAddressPrivateKey", "1exampleToAddress", 123456);
```
Sends bitcoin from one of your addresses to the destination address. Amount is measured in bits.

```
$razrbit->walletGetBalanceFromAddress($exampleAddress);
```
Returns the balance of the given address in bits.


### Explorer

```
$razrbit->explorerBlock($exampleBlock);
```
Retrieve details about a given block

```
$razrbit->explorerTransaction($exampleTransaction);
```
Retrieve details about a given transaction

```
$razrbit->explorerAddress($exampleAddress);
```
Retrieve details about a given address

```
$razrbit->explorerAddressUnspentOutputs($exampleAddress);
```
Returns the list of unspent outputs for a given address

### Network

```
$razrbit->networkGetDifficulty();
```
Retrieve the current network difficulty

```
$razrbit->networkPushTransaction("exampleTransaction");
```
Push a transaction onto the network

### Markets

```currencyCode``` is a valid ISO 4217 code such as ```USD``` or ```EUR```.

```
$razrbit->marketsPrice("USD");
```
Returns the current bitcoin price

```
$razrbit->marketsDayPrice("USD");
```
Returns the day price

```
$razrbit->marketsHistoricalPrice("USD","2014-03-03");
```
Returns the historical price at the given date. ```date``` must be a date in the ```yyyy-mm-dd``` format.

### Webhooks

```
$razrbit->notificationAddress($exampleAddress,"example@example.com");
```
Set up a notification email for a given address

```
$razrbit->notificationBlock($blockHash,"example@example.com");
```
Set up a notification email for a given block

```
$razrbit->notificationTransaction($transactionHash,"example@example.com");
```
Set up a notification email for a given transaction

# License

**Razrbit code released under [the MIT license](https://github.com/LUXSTACK/razrbit-sdk-php/blob/master/LICENSE).**

Copyright 2012-2014 LUXSTACK Inc. Razrbit is a trademark maintained by LUXSTACK Inc.

# Razrbit Bitcoin SDKs for other platforms

* [Android](https://github.com/LUXSTACK/razrbit-sdk-android)
* [iOS](https://github.com/LUXSTACK/razrbit-sdk-ios)
* [Javascript](https://github.com/LUXSTACK/razrbit-sdk-javascript)
* PHP
* [Ruby](https://github.com/LUXSTACK/razrbit-sdk-ruby)

# Package author

This package was made by [Freddie Coleman](http://www.freddiecoleman.com)