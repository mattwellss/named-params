<?php

namespace Mpw;

class NamedParamFunctionCall
{
    /**
     * @var array
     */
    private $args = [];

    /**
     * @var \ReflectionFunction
     */
    protected $reflection;

    /**
     * @param callable $subject
     * @param array    $arguments
     */
    public function __construct(callable $subject, array $arguments = [])
    {
        $this->reflection = new \ReflectionFunction($subject);
    }

    /**
     * Merge the stored arguments with any that may have been passed in
     * @param  array  $arguments
     * @return array
     */
    protected function prepareArgs(array $arguments = [])
    {
        $arguments = $arguments + array_filter(array_map(function ($arg) {
            if (isset($this->args[$arg->getName()])) {
                return $this->args[$arg->getName()];
            }

            if ($arg->isDefaultValueAvailable()) {
                return $arg->getDefaultValue();
            }

            return null;
        }, $this->reflection->getParameters()));

        return $arguments;
    }

    /**
     * Call the associated function
     * @param  array $arguments
     * @return mixed
     */
    public function __invoke(...$arguments)
    {
        $arguments = $this->prepareArgs($arguments);

        return $this->reflection->invokeArgs($arguments ?: []);
    }

    /**
     * Create a version of this object with the given argument
     * @param  string $argName
     * @param  mixed $argValue
     * @return \Mpw\NamedParamFunctionCall
     */
    public function withArg($argName, $argValue)
    {
        $new = clone $this;
        $new->args[$argName] = $argValue;
        return $new;
    }
}
