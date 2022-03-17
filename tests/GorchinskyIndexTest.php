<?php

use PHPUnit\Framework\TestCase;
use Soandso\ContinentalIndex\GorchinskyIndex;

class GorchinskyIndexTest extends TestCase
{
    private $gorchinsky;

    protected function setUp(): void
    {
        $this->gorchinsky = new GorchinskyIndex();
    }

    protected function tearDown(): void
    {
        unset($this->gorchinsky);
    }

    public function testSuccessCalcIndex()
    {
        $actual = 65.4;

        $this->assertEquals($this->gorchinsky->calcIndex(28.5, 47.8), $actual);
    }

    public function testErrorCalcIndex()
    {
        $actual = 72.5;

        $this->assertNotEquals($this->gorchinsky->calcIndex(28.5, 47.8), $actual);
    }

    public function testSuccessGetIndexAssets()
    {
        $actual = [
            [2020, 41.54],
            [2021, 58.52],
        ];

        $tempAmplitudeData = [
            [2020, 18.1],
            [2021, 25.5]
        ];

        $latitude = 47.8;

        $this->assertEquals($this->gorchinsky->getIndexAssets($tempAmplitudeData, $latitude), $actual);
    }

    public function testErrorGetIndexAssets()
    {
        $actual = [
            [2020, 42.54],
            [2021, 58.52],
        ];

        $tempAmplitudeData = [
            [2020, 18.1],
            [2021, 25.5]
        ];

        $latitude = 47.8;

        $this->assertNotEquals($this->gorchinsky->getIndexAssets($tempAmplitudeData, $latitude), $actual);
    }
}