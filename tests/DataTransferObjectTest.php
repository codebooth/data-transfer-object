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
}
