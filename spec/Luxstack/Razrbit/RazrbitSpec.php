<?php

namespace spec\Luxstack\Razrbit;

// Demo App Id and Secret
CONST MY_APP_ID     = "A25AOpLUoT";
CONST MY_APP_SECRET = "688e2b77-09a3-4945-9468-bf188ff3de88";

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RazrbitSpec extends ObjectBehavior
{
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
}
