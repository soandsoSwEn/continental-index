<?php

use PHPUnit\Framework\TestCase;
use Soandso\ContinentalIndex\ZenkerIndex;

class ZenkerIndexTest extends TestCase
{
    private $zenker;

    protected function setUp(): void
    {
        $this->zenker = new ZenkerIndex();
    }

    protected function tearDown(): void
    {
        unset($this->zenker);
    }

    public function testSuccessCalcIndex()
    {
        $actual = 22.35;

        $this->assertEquals($this->zenker->calcIndex(29.5, 49.8), $actual);
    }

    public function testErrorCalcIndex()
    {
        $actual = 25.35;

        $this->assertNotEquals($this->zenker->calcIndex(29.5, 49.8), $actual);
    }

    public function testSuccessGetIndexAssets()
    {
        $actual = [
            [2020, 9.21],
            [2021, 24.27],
        ];

        $tempAmplitudeData = [
            [2020, 20.5],
            [2021, 29.8]
        ];

        $latitude = 47.8;

        $this->assertEquals($this->zenker->getIndexAssets($tempAmplitudeData, $latitude), $actual);
    }

    public function testErrorGetIndexAssets()
    {
        $actual = [
            [2020, 9.25],
            [2021, 24.27],
        ];

        $tempAmplitudeData = [
            [2020, 20.5],
            [2021, 29.8]
        ];

        $latitude = 47.8;

        $this->assertNotEquals($this->zenker->getIndexAssets($tempAmplitudeData, $latitude), $actual);
    }
}