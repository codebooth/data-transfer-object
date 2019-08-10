<?php

declare(strict_types=1);

namespace Codebooth\DataTransferObject;

namespace CodeBooth\DataTransferObject;

use CodeBooth\DataTransferObject\Exceptions\DataException;
use ReflectionClass;
use ReflectionProperty;

abstract class DataTransferObject
{
    /**
     * Array of casts to be performed. Keys are attribute names, values are type casts.
     *
     * @var array
     */
    public $casts = [];

    /**
     * DataTransferObject constructor.
     *
     * @param array $attributes
     *
     * @throws \ReflectionException
     * @throws \CodeBooth\DataTransferObject\Exceptions\DataException
     */
    public function __construct(array $attributes)
    {
        $class = new ReflectionClass(static::class);

        $properties = $this->getProperties($class);

        foreach ($properties as $property) {
            $propertyName = $property->getName();

            if ($this->isReservedPropertyName($property->getName())) {
                continue;
            }

            if (! isset($attributes[$propertyName])) {
                throw new DataException("Property `{$property->getName()}` has not been initialized");
            }

            $property->value($attributes[$propertyName] ?? $property->getValue());

            unset($attributes[$propertyName]);
        }

        if (! empty($attributes)) {
            throw new DataException('No mapping properties provided');
        }
    }

    /**
     * Returns list of all properties and their value.
     *
     * @return array
     * @throws \ReflectionException
     */
    public function all()
    {
        $data = [];

        $class = new ReflectionClass(static::class);

        $properties = $class->getProperties(ReflectionProperty::IS_PUBLIC);

        foreach ($properties as $reflectionProperty) {
            if ($this->isReservedPropertyName($reflectionProperty->getName())) {
                continue;
            }

            $data[$reflectionProperty->getName()] = $reflectionProperty->getValue($this);
        }

        return $data;
    }

    /**
     * @param \ReflectionClass $class
     *
     * @return array|\CodeBooth\DataTransferObject\Property[]
     * @throws \ReflectionException
     */
    protected function getProperties(ReflectionClass $class)
    {
        $properties = [];

        foreach ($class->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            $properties[$property->getName()] = new Property($this, $property);
        }

        return $properties;
    }

    /**
     * Check if the property name is reserved.
     *
     * @param string $propertyName
     *
     * @return bool
     */
    protected function isReservedPropertyName(string $propertyName)
    {
        return in_array($propertyName, ['casts']);
    }
}
