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

        $this->dto->{$property} = $value;
    }
}
