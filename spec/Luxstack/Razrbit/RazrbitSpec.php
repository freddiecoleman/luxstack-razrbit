<?php

namespace spec\Luxstack\Razrbit;

// Demo App Id and Secret
CONST MY_APP_ID     = "A25AOpLUoT";
CONST MY_APP_SECRET = "688e2b77-09a3-4945-9468-bf188ff3de88";

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RazrbitSpec extends ObjectBehavior {

    function let()
    {
        $this->beConstructedWith(MY_APP_ID, MY_APP_SECRET);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Luxstack\Razrbit\Razrbit');
    }

    function it_creates_a_new_address()
    {
        $this->walletCreateNewAddress()
            ->shouldMatch('/[A-z0-9]{26,34}/');
    }

    function it_sends_bitcoin()
    {
        $this->walletSendAmount("5exampleFromAddressPrivateKey", "1exampleToAddress", 123456)
            ->shouldReturn(true);
    }

    function it_gets_the_balance_from_an_address()
    {
        $this->walletGetBalanceFromAddress("12sENwECeRSmTeDwyLNqwh47JistZqFmW8")
            ->shouldBeDouble();
    }

    function it_retrieves_details_about_a_given_block()
    {
        $this->explorerBlock("000000000000000021c40d35f9c317d2e8c9ead4dec3e24b8d1919862bd8f89d")
            ->shouldBeArray();
    }

    function it_retrieves_details_about_a_given_transaction()
    {
        $this->explorerTransaction("39f35e6a1c69e13342e6cad3471ec247d0c4b45594aa59715c1d109c62363208")
            ->shouldBeArray();
    }

    function it_retrieves_details_about_a_given_address()
    {
        $this->explorerAddress("12sENwECeRSmTeDwyLNqwh47JistZqFmW8")
            ->shouldBeArray();
    }

    function it_returns_unspent_outputs_for_an_address()
    {
        $this->explorerAddressUnspentOutputs("12sENwECeRSmTeDwyLNqwh47JistZqFmW8")
            ->shouldBeArray();
    }

    function it_returns_the_current_network_difficulty()
    {
        $this->networkGetDifficulty()
            ->shouldBeDouble();
    }

    function it_pushes_a_transaction_to_the_network()
    {
        $this->networkPushTransaction("exampleTransaction")
            ->shouldReturn(true);
    }

    function it_returns_the_current_market_price()
    {
        $this->marketsPrice("USD")->shouldBeDouble();
    }

}
