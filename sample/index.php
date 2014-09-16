<?php

use Luxstack\Razrbit\Razrbit;

require_once '../vendor/autoload.php';

error_reporting(E_ALL);
ini_set('display_errors', '1');

CONST MY_APP_ID     = "A25AOpLUoT";           // fill in your Razrbit Application ID
CONST MY_APP_SECRET = "688e2b77-09a3-4945-9468-bf188ff3de88";       // fill in your Razrbit Application Secret

$razrbit = new Razrbit(MY_APP_ID, MY_APP_SECRET);
print_r($razrbit->walletCreateNewAddress());

if ($razrbit->walletSendAmount("5exampleFromAddressPrivateKey", "1exampleToAddress", 123456))
{
    print_r("sent okay\n");
} else
{
    print_r("not sent\n");
}

print_r($razrbit->walletGetBalanceFromAddress("12sENwECeRSmTeDwyLNqwh47JistZqFmW8"));

print_r($razrbit->explorerBlock("000000000000000021c40d35f9c317d2e8c9ead4dec3e24b8d1919862bd8f89d"));

print_r($razrbit->explorerTransaction("39f35e6a1c69e13342e6cad3471ec247d0c4b45594aa59715c1d109c62363208"));

print_r($razrbit->explorerAddress("12sENwECeRSmTeDwyLNqwh47JistZqFmW8"));

print_r($razrbit->explorerAddressUnspentOutputs("12sENwECeRSmTeDwyLNqwh47JistZqFmW8"));

print_r($razrbit->networkGetDifficulty());

print_r($razrbit->networkPushTransaction("exampleTransaction"));

print_r($razrbit->marketsPrice("USD"));

print_r($razrbit->marketsDayPrice("USD"));

print_r($razrbit->marketsHistoricalPrice("USD", "2014-03-03"));

print_r($razrbit->notificationsAddress("12sENwECeRSmTeDwyLNqwh47JistZqFmW8", "example@example.com"));

print_r($razrbit->notificationsBlock("000000000000000021c40d35f9c317d2e8c9ead4dec3e24b8d1919862bd8f89d", "example@example.com"));

print_r($razrbit->notificationsTransaction("000000000000000021c40d35f9c317d2e8c9ead4dec3e24b8d1919862bd8f89d", "example@example.com"));

?>
