<?php

namespace spec\AppBundle\Domain;

use AppBundle\Domain\PgnDate;
use PhpSpec\ObjectBehavior;

class PgnDateSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->beConstructedThrough('fromString', ['1934.??.??']);
        $this->shouldHaveType(PgnDate::class);
        $this->getYear()->shouldReturn(1934);
        $this->getMonth()->shouldReturn(0);
        $this->getDay()->shouldReturn(0);
        $this->toString()->shouldReturn('1934.??.??');
    }
}
