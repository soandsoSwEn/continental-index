<?php

namespace Soandso\ContinentalIndex\Tests;

use bovigo\vfs\vfsStream;
use Mockery;
use PHPUnit\Framework\TestCase;
use Soandso\ContinentalIndex\ConradIndex;
use Soandso\ContinentalIndex\Data\SourceData;
use Soandso\ContinentalIndex\GorchinskyIndex;
use Soandso\ContinentalIndex\HromovIndex;
use Soandso\ContinentalIndex\Index;
use Soandso\ContinentalIndex\ZenkerIndex;

class IndexTest extends TestCase
{
    private $root;

    private $index;

    private $fileName = 'amplitude.txt';

    protected function setUp(): void
    {
        $this->root = vfsStream::setup('rootDir');
        $this->index = new Index('hromov', 'file', vfsStream::url('rootDir'));
    }

    protected function tearDown(): void
    {
        unset($this->index);
        Mockery::close();
    }

    public function testGetIndexTypes()
    {
        $actual = [
            'hromov' => HromovIndex::class,
            'gorchinsky' => GorchinskyIndex::class,
            'conrad' => ConradIndex::class,
            'zenker' => ZenkerIndex::class,
        ];

        $this->assertEquals($this->index->getIndexTypes(), $actual);
    }

    public function testGetOutputFormats()
    {
        $actual = [
            'file', 'array', 'json',
        ];

        $this->assertEquals($this->index->getOutputFormats(), $actual);
    }

    public function testGetFilePath()
    {
        $actual = 'vfs://rootDir';

        $this->assertEquals($this->index->getFilePath(), $actual);
    }

    public function testGetFileOutputForHromovIndex()
    {
        $actual = 'hromov_continental_indices.txt';

        $reflector = new \ReflectionClass(Index::class);
        $method = $reflector->getMethod('setFileOutput');
        $method->setAccessible(true);
        $result = $method->invokeArgs($this->index, ['hromov']);

        $this->assertEquals($this->index->getFileOutput(), $actual);
    }

    public function testGetFileOutputForGorchinskyIndex()
    {
        $actual = 'gorchinsky_continental_indices.txt';

        $reflector = new \ReflectionClass(Index::class);
        $method = $reflector->getMethod('setFileOutput');
        $method->setAccessible(true);
        $result = $method->invokeArgs($this->index, ['gorchinsky']);

        $this->assertEquals($this->index->getFileOutput(), $actual);
    }

    public function testBuildFileOutput()
    {
        $indexAssets = [
            [2020, 0.78],
            [2021, 0.84],
        ];

        $this->assertTrue($this->index->buildFileOutput($indexAssets));
    }

    public function testBuildJsonOutput()
    {
        $actual = '[[2020,0.78],[2021,0.84]]';

        $indexAssets = [
            [2020, 0.78],
            [2021, 0.84],
        ];

        $this->assertEquals($this->index->buildJsonOutput($indexAssets), $actual);
    }

    public function testGetIndexAssetsForFile()
    {
        $assimilateData = [
            [2020, 18.1],
            [2021, 25.3]
        ];

        $latitude = 47.8;

        $source = Mockery::mock(SourceData::class);
        $source->shouldReceive('getAssimilateData')->once()->andReturn($assimilateData);
        $source->shouldReceive('getLatitude')->once()->andReturn($latitude);

        $this->assertTrue($this->index->getIndexAssets($source));
    }

    public function testGetIndexAssetsForArray()
    {
        $this->index = new Index('hromov', 'array');

        $actual = [
            [2020, 0.78],
            [2021, 0.84],
        ];

        $assimilateData = [
            [2020, 18.1],
            [2021, 25.3]
        ];

        $latitude = 47.8;

        $source = Mockery::mock(SourceData::class);
        $source->shouldReceive('getAssimilateData')->once()->andReturn($assimilateData);
        $source->shouldReceive('getLatitude')->once()->andReturn($latitude);

        $this->assertEquals($this->index->getIndexAssets($source), $actual);
    }

    public function testErrorGetIndexAssetsForArray()
    {
        $this->index = new Index('hromov', 'array');

        $actual = [
            [2020, 0.79],
            [2021, 0.84],
        ];

        $assimilateData = [
            [2020, 18.1],
            [2021, 25.3]
        ];

        $latitude = 47.8;

        $source = Mockery::mock(SourceData::class);
        $source->shouldReceive('getAssimilateData')->once()->andReturn($assimilateData);
        $source->shouldReceive('getLatitude')->once()->andReturn($latitude);

        $this->assertNotEquals($this->index->getIndexAssets($source), $actual);
    }

    public function testGetIndexAssetsForJson()
    {
        $this->index = new Index('hromov', 'json');

        $actual = '[[2020,0.78],[2021,0.84]]';

        $assimilateData = [
            [2020, 18.1],
            [2021, 25.3]
        ];

        $latitude = 47.8;

        $source = Mockery::mock(SourceData::class);
        $source->shouldReceive('getAssimilateData')->once()->andReturn($assimilateData);
        $source->shouldReceive('getLatitude')->once()->andReturn($latitude);

        $this->assertEquals($this->index->getIndexAssets($source), $actual);
    }

    public function testErrorGetIndexAssetsForJson()
    {
        $this->index = new Index('hromov', 'json');

        $actual = '[[2020,0.78],[2021,0.85]]';

        $assimilateData = [
            [2020, 18.1],
            [2021, 25.3]
        ];

        $latitude = 47.8;

        $source = Mockery::mock(SourceData::class);
        $source->shouldReceive('getAssimilateData')->once()->andReturn($assimilateData);
        $source->shouldReceive('getLatitude')->once()->andReturn($latitude);

        $this->assertNotEquals($this->index->getIndexAssets($source), $actual);
    }
}