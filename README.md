# Luxstack Razrbit SDK for PHP (Beta)

This package contains the Luxstack Razrbit Bitcoin platform - allowing you to build, test and scale bitcoin apps faster.

I did not create this, it is built by Luxstack. I have simply written some automated tests for it and am distributing it as a composer package.

## Installation

Begin by installing this package through Composer.

```js
{
    "require": {
		"freddiecoleman/luxstack-razrbit": "~1.0"
	}
}
```

## Usage - REST API Calls

Prior to making any REST API calls, invoke the init method passing in your AppId and AppSecret which will be used for all subsequent invocations:

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

**Code released under [the MIT license](https://github.com/LUXSTACK/razrbit-sdk-php/blob/master/LICENSE).**

Copyright 2012-2014 LUXSTACK Inc. Razrbit is a trademark maintained by LUXSTACK Inc.

# Razrbit Bitcoin SDKs for other platforms

* [Android](https://github.com/LUXSTACK/razrbit-sdk-android)
* [iOS](https://github.com/LUXSTACK/razrbit-sdk-ios)
* [Javascript](https://github.com/LUXSTACK/razrbit-sdk-javascript)
* PHP
* [Ruby](https://github.com/LUXSTACK/razrbit-sdk-ruby)