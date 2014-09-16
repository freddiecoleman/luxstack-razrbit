<?php

namespace spec\Luxstack\Razrbit;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RazrbitSpec extends ObjectBehavior
{
    function let($appId = 'A25AOpLUoT',$appSecret = '688e2b77-09a3-4945-9468-bf188ff3de88')
    {
        $this->beConstructedWith($appId, $appSecret);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Luxstack\Razrbit\Razrbit');
    }
}
