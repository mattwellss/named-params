<?php

namespace Mpw;

class NamedParamMethodCall extends NamedParamFunctionCall
{

    /**
     * @var \object
     */
    private $object;

    /**
     * @param callable $object
     * @param array    $arguments
     */
    public function __construct(callable $object, array $arguments = [])
    {

        if (is_array($object)) {
            list($object, $method) = $object;
            $this->object = $object;
        }

        $this->reflection = isset($method) ?
            new \ReflectionMethod($object, $method) :
            new \ReflectionMethod($object);
    }

    /**
     * call the method!!
     * @param  array $arguments
     * @return mixed
     */
    public function __invoke(...$arguments)
    {
        $arguments = $this->prepareArgs($arguments);

        return $this->reflection->invokeArgs($this->object, $arguments);
    }
}
