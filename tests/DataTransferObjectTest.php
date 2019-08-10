<?php

declare(strict_types=1);

namespace CodeBooth\DataTransferObject\Tests;

use CodeBooth\DataTransferObject\DataTransferObject;
use CodeBooth\DataTransferObject\Exceptions\DataException;
use PHPUnit\Framework\TestCase;

class DataTransferObjectTest extends TestCase
{
    /** @test */
    public function is_should_support_only_public_properties()
    {
        $input = [
            'publicProperty' => 'example',
        ];

        $testClass = new class($input) extends DataTransferObject
        {
            public $publicProperty;

            protected $protectedProperty;
        };

        $properties = $testClass->all();

        $this->assertCount(1, $properties);
        $this->assertArrayHasKey('publicProperty', $properties);
        $this->assertArrayNotHasKey('protectedProperty', $properties);
    }

    /** @test */
    public function it_should_throw_error_for_uninitialized_property()
    {
        $this->expectException(DataException::class);
        $this->expectExceptionMessage('Property `notProvidedProperty` has not been initialized');

        $input = [
            'publicProperty' => 'example',
        ];

        new class($input) extends DataTransferObject
        {
            public $publicProperty;

            public $notProvidedProperty;
        };
    }

    /** @test */
    public function it_should_throw_error_for_not_mapped_property()
    {
        $this->expectException(DataException::class);
        $this->expectExceptionMessage('No mapping properties provided');

        $input = [
            'publicProperty' => 'example',
            'nonMapped' => 'this should throw an error',
        ];

        new class($input) extends DataTransferObject
        {
            public $publicProperty;
        };
    }

    /** @test */
    public function it_should_cast_to_int()
    {
        $input = [
            'property1' => '123',
            'property2' => '123',
            'property3' => '123',
        ];

        $testClass = new class($input) extends DataTransferObject {
            public $property1;
            public $property2;
            public $property3;

            public $casts = [
                'property1' => 'int',
                'property2' => 'integer',
            ];
        };

        $this->assertIsInt($testClass->property1);
        $this->assertIsInt($testClass->property2);
        $this->assertIsString($testClass->property3);
    }

    /** @test */
    public function it_should_cast_to_float()
    {
        $input = [
            'property1' => '123.45',
            'property2' => '123.45',
            'property3' => '123.45',
            'property4' => '123.45',
        ];

        $testClass = new class($input) extends DataTransferObject {
            public $property1;
            public $property2;
            public $property3;
            public $property4;

            public $casts = [
                'property1' => 'real',
                'property2' => 'float',
                'property3' => 'double',
            ];
        };

        $this->assertIsFloat($testClass->property1);
        $this->assertIsFloat($testClass->property2);
        $this->assertIsFloat($testClass->property3);
        $this->assertIsString($testClass->property4);
    }
}
