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

    public function testSuccessCalcIndex()
    {
        $actual = 59.27;

        $this->assertEquals($this->conrad->calcIndex(29.5, 47.8), $actual);
    }

    public function testErrorCalcIndex()
    {
        $actual = 59.28;

        $this->assertNotEquals($this->conrad->calcIndex(29.5, 47.8), $actual);
    }

    public function testSuccessGetIndexAssets()
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

    public function testErrorGetIndexAssets()
    {
        $actual = [
            [2020, 40.39],
            [2021, 60.15],
        ];

        $tempAmplitudeData = [
            [2020, 20.1],
            [2021, 29.8]
        ];

        $latitude = 47.8;

        $this->assertNotEquals($this->conrad->getIndexAssets($tempAmplitudeData, $latitude), $actual);
    }
}