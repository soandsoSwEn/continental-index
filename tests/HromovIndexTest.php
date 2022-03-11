<?php

namespace Soandso\ContinentalIndex\Tests;

use PHPUnit\Framework\TestCase;
use Soandso\ContinentalIndex\HromovIndex;

class HromovIndexTest extends TestCase
{
    private $hromovIndex;

    protected function setUp(): void
    {
        $this->hromovIndex = new HromovIndex();
    }

    protected function tearDown(): void
    {
        unset($this->hromovIndex);
    }

    public function testCalcIndex()
    {
        $actual = 0.87;

        $this->assertEquals($this->hromovIndex->calcIndex(30.5, 47.8), $actual);
    }

    public function testGetIndexAssets()
    {
        $actual = [
            [2020, 0.78],
            [2021, 0.84],
        ];

        $tempAmplitudeData = [
            [2020, 18.1],
            [2021, 25.3]
        ];

        $longitude = 47.8;

        $this->assertEquals($this->hromovIndex->getIndexAssets($tempAmplitudeData, $longitude), $actual);
    }
}