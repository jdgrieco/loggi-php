<?php

namespace JansenFelipe\LoggiPHP;

class Mutation
{
    /**
     * @var array
     */
    private $value;

    /**
     * Query constructor.
     *
     * @param array $value
     */
    public function __construct(array $value)
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return 'mutation ' . $this->parse($this->value);
    }

    private function parse(array $array = [])
    {
        $return = '';

        foreach ($array as $key => $value) {

            if (!is_int($key)) {
                $return .= $key . ' ';
            }

            if (is_array($value)) {
                $value = $this->parse($value);
            }

            $return .= $value . ' ';
        }

        $return .= ' }';

        return '{ ' . $return . ' }';
    }
}