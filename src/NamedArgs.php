<?php
namespace HedgebotApi;

class NamedArgs
{
    private $args;

    public function __construct(array $args = array())
    {
        $this->args = $args;
    }

    public function __get($name)
    {
        if (isset($this->$name)) {
            return $this->$name;
        }

        return null;
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    public function toArray()
    {
        return $this->args;
    }
}
