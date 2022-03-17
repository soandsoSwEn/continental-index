<?php

use PHPUnit\Framework\TestCase;
use Soandso\ContinentalIndex\ConradIndex;

class ConradIndexTest extends TestCase
{
    private $conrad;

    protected function setUp(): void
    {
        $this->conrad = new ConradIndex();
    }

    protected function tearDown(): void
    {
        unset($this->conrad);
    }

    public function testCalcIndex()
    {
        $actual = 59.27;

        $this->assertEquals($this->conrad->calcIndex(29.5, 47.8), $actual);
    }

    public function testGetIndexAssets()
    {
        $actual = [
            [2020, 40.38],
            [2021, 59.87],
        ];

        $tempAmplitudeData = [
            [2020, 20.1],
            [2021, 29.8]
        ];

        $latitude = 47.8;

        $this->assertEquals($this->conrad->getIndexAssets($tempAmplitudeData, $latitude), $actual);
    }
}