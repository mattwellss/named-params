<?php

namespace spec\Mpw;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

function zero()
{
    return "success!";
}

function one_arg($value)
{
    return $value;
}

function many_args($one, $two, $three, $four, $five='wahoo')
{
    return $one . $two . $three . $four . $five;
}

function one_optional($one, $two = 'two')
{
    return $one . $two;
}

class NamedParamFunctionCallSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->beConstructedWith('is_null');
        $this->shouldHaveType('Mpw\NamedParamFunctionCall');
    }

    function it_can_call_a_function_with_zero_arguments()
    {
        $this->beConstructedWith('spec\Mpw\zero');
        $this->__invoke()->shouldEqual('success!');
    }

    function it_can_call_a_function_with_one_argument()
    {
        $this->beConstructedWith('spec\Mpw\one_arg');
        $saysHello = $this->withArg('value', 'Hello, world!');
        $saysHello->__invoke()->shouldEqual('Hello, world!');
    }

    function it_can_call_a_function_with_many_arguments()
    {
        $this->beConstructedWith('spec\Mpw\many_args');
        $this
            ->withArg('two', 'two')
            ->withArg('one', 'one')
            ->withArg('four', 'four')
            ->withArg('three', 'three')
            ->withArg('five', 'five')
            ->__invoke()
                ->shouldEqual('onetwothreefourfive');
    }

    function it_will_use_default_values_if_none_other_supplied()
    {
        $this->beConstructedWith('spec\Mpw\one_optional');

        $callme = $this->withArg('one', 'one');
        $callme->__invoke()->shouldEqual('onetwo');
    }

    function it_can_be_called_mulitple_times_with_different_arguments()
    {
        $this->beConstructedWith('spec\Mpw\many_args');

        $allCalls = $this
                        ->withArg('one', 'one')
                        ->withArg('two', 'two');

        $secondCall = $allCalls
                        ->withArg('four', 'quattro')
                        ->withArg('three', 'tres')
                        ->withArg('five', 'cinco');

        $firstCall = $allCalls
                        ->withArg('three', 'three')
                        ->withArg('four', 'four')
                        ->withArg('five', 'five');

        $secondCall->__invoke()->shouldEqual('onetwotresquattrocinco');
        $firstCall->__invoke()->shouldEqual('onetwothreefourfive');
    }

    function it_can_use_arguments_supplied_in_call()
    {
        $this->beConstructedWith('spec\Mpw\many_args');

        $this->__invoke('one', 'two', 'three', 'four', 'five')->shouldEqual('onetwothreefourfive');
    }

    function it_can_use_a_mix_of_call_and_witharg()
    {
        $this->beConstructedWith('spec\Mpw\many_args');

        $call = $this->withArg('four', 'four');
        $call = $call->withArg('five', 'five');
        $call->__invoke('one', 'two', 'three')->shouldEqual('onetwothreefourfive');
    }
}
