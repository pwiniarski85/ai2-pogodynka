<?php

namespace App\Tests\Entity;

use App\Entity\Measurement;
use PHPUnit\Framework\TestCase;

class MeasurementTest extends TestCase
{
    public function dataGetFahrenheit(): array
    {
        return [
            ['0', 32],
            ['-100', -148],
            ['100', 212],
            ['1', 33.8],
            ['19', 66.2],
            ['23.3', 73.94],
            ['35.2', 95.36],
            ['-13.9', 6.98],
            ['-77.4', -107.32],
            ['1000', 1832]
        ];
    }
    /**
    * @dataProvider dataGetFahrenheit
    */

    public function testGetFahrenheit($celsius, $expectedFahrenheit): void
    {
        $measurement = new Measurement();
        $measurement -> setCelsius($celsius);
        $this->assertTrue($expectedFahrenheit == $measurement->getFahrenheit());
    }
}