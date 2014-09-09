<img src="http://cdn.luxstack.com/assets/razrbit-github-banner-dark-beta.png" style="width:100%"/>

Official SDKs: 
[Android](https://github.com/LUXSTACK/razrbit-sdk-android) | 
[iOS](https://github.com/LUXSTACK/razrbit-sdk-ios) | 
[Javascript](https://github.com/LUXSTACK/razrbit-sdk-javascript) | 
PHP | 
[Ruby](https://github.com/LUXSTACK/razrbit-sdk-ruby)

**[Razrbit](https://luxstack.com) Bitcoin Platform and SDKs — build, test and scale bitcoin apps faster with Razrbit. Plug in our powerful SDKs to supercharge your bitcoin toolbox.**

# Razrbit SDK for PHP (Beta)

## Installation

In the library section of your project, clone the API:

```
git clone https://github.com/LUXSTACK/razrbit-sdk-php.git
```

## Usage - REST API Calls


Be sure to import the razrbit.php file where needed:

```
require_once 'razrbit-sdk-php/src/razrbit.php';
```

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

# Support

Feel free to request a feature and make suggestions for our [product team](mailto:team@luxstack.com).

* [GitHub Issues](https://github.com/luxstack/razrbit-sdk-php/issues)

# License

**Code released under [the MIT license](https://github.com/LUXSTACK/razrbit-sdk-php/blob/master/LICENSE).**

Copyright 2012-2014 LUXSTACK Inc. Razrbit is a trademark maintained by LUXSTACK Inc.

# Razrbit Bitcoin SDKs for other platforms

* [Android](https://github.com/LUXSTACK/razrbit-sdk-android)
* [iOS](https://github.com/LUXSTACK/razrbit-sdk-ios)
* [Javascript](https://github.com/LUXSTACK/razrbit-sdk-javascript)
* PHP
* [Ruby](https://github.com/LUXSTACK/razrbit-sdk-ruby)