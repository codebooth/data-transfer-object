<?php

declare(strict_types=1);

namespace CodeBooth\DataTransferObject;

use ReflectionProperty;

class Property extends ReflectionProperty
{
    /**
     * @var \CodeBooth\DataTransferObject\DataTransferObject
     */
    protected $dto;

    /**
     * Property constructor.
     *
     * @param \CodeBooth\DataTransferObject\DataTransferObject $object
     * @param \ReflectionProperty $reflectionProperty
     *
     * @throws \ReflectionException
     */
    public function __construct(DataTransferObject $object, ReflectionProperty $reflectionProperty)
    {
        parent::__construct($reflectionProperty->class, $reflectionProperty->getName());

        $this->dto = $object;
    }

    /**
     * @param mixed $value
     */
    public function value($value)
    {
        $property = $this->getName();

        if ($this->hasCast($property)) {
            $value = $this->cast($value);
        }

        $this->dto->{$this->getName()} = $value;
    }

    /**
     * Check if the attribute's value needs to cast.
     *
     * @param string $attribute
     *
     * @return bool
     */
    protected function hasCast(string $attribute)
    {
        return array_key_exists($attribute, $this->dto->casts);
    }

    /**
     * Cast an attribute to a native PHP type.
     *
     * @param mixed $value
     *
     * @return mixed
     */
    protected function cast($value)
    {
        if (is_null($value)) {
            return $value;
        }

        switch ($this->getCastType($this->getName())) {
            case 'int':
            case 'integer':
                return (int) $value;

            case 'real':
            case 'float':
            case 'double':
                return (float) $value;

            default:
                $value;
        }
    }

    /**
     * Get the type of cast for attribute.
     *
     * @param string $attribute
     *
     * @return string
     */
    protected function getCastType(string $attribute)
    {
        return trim(strtolower($this->dto->casts[$attribute]));
    }
}
