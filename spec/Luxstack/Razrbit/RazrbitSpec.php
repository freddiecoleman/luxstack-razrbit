<?php

namespace spec\Luxstack\Razrbit;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RazrbitSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Luxstack\Razrbit\Razrbit');
    }
}
