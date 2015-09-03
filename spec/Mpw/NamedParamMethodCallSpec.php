<?php

namespace spec\Mpw;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

require __DIR__ . '/Test.php';

class NamedParamMethodCallSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->beConstructedWith([new Test, 'zero']);
        $this->shouldHaveType('Mpw\NamedParamMethodCall');
    }

    function it_can_call_a_method_with_zero_arguments()
    {
        $this->beConstructedWith([new Test, 'zero']);
        $this->__invoke()->shouldEqual('success!');
    }

    function it_can_call_a_method_with_one_argument()
    {
        $this->beConstructedWith([new Test, 'oneArg']);
        $saysHello = $this->withArg('value', 'Hello, world!');
        $saysHello->__invoke()->shouldEqual('Hello, world!');
    }

    function it_can_call_a_method_with_many_arguments()
    {
        $this->beConstructedWith([new Test, 'manyArgs']);
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
        $this->beConstructedWith([new Test, 'oneOptional']);

        $callme = $this->withArg('one', 'one');
        $callme->__invoke()->shouldEqual('onetwo');
    }

    function it_can_be_called_mulitple_times_with_different_arguments()
    {
        $this->beConstructedWith([new Test, 'manyArgs']);

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
        $this->beConstructedWith([new Test, 'manyArgs']);

        $this->__invoke('one', 'two', 'three', 'four', 'five')->shouldEqual('onetwothreefourfive');
    }

    function it_can_use_a_mix_of_call_and_witharg()
    {
        $this->beConstructedWith([new Test, 'manyArgs']);

        $call = $this->withArg('four', 'four');
        $call = $call->withArg('five', 'five');
        $call->__invoke('one', 'two', 'three')->shouldEqual('onetwothreefourfive');
    }

    function it_can_make_static_calls()
    {
        $this->beConstructedWith('spec\Mpw\Test::staticCallable');

        $this->__invoke()->shouldEqual('static');
    }
}
