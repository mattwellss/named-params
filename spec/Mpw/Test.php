<?php
namespace spec\Mpw;

class Test {
    function zero()
    {
        return "success!";
    }

    function oneArg($value)
    {
        return $value;
    }

    function manyArgs($one, $two, $three, $four, $five='wahoo')
    {
        return $one . $two . $three . $four . $five;
    }

    function oneOptional($one, $two = 'two')
    {
        return $one . $two;
    }

    static function staticCallable()
    {
        return 'static';
    }
}
