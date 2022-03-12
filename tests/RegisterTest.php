<?php

namespace Soandso\ContinentalIndex\Tests;

use bovigo\vfs\vfsStream;
use Mockery;
use PHPUnit\Framework\TestCase;
use Soandso\ContinentalIndex\Data\SourceData;
use Soandso\ContinentalIndex\Register;

class RegisterTest extends TestCase
{
    private $register;

    private $root;

    protected function setUp(): void
    {
        $this->root = vfsStream::setup('rootDir');
    }

    protected function tearDown(): void
    {
        Mockery::close();
        unset($this->root);
    }

    public function testSetData()
    {
        $sourceData = [
            [2020, 18.1],
            [2021, 25.3]
        ];

        $fileName = 'amplitude.txt';
        $content = "2017 80.9" . PHP_EOL . "2018 70.3";
        $file = vfsStream::newFile($fileName)
            ->withContent($content)
            ->at($this->root);

        $this->register = new Register('file', $file->url(), 'F', 'C', 47.8);

        $reflector = new \ReflectionClass(Register::class);
        $method = $reflector->getMethod('setData');
        $method->setAccessible(true);
        $result = $method->invokeArgs($this->register, ['array', $sourceData, 'F', 'C', 47.8]);
        $property = $reflector->getProperty('sourceData');
        $property->setAccessible(true);
        $value = $property->getValue($this->register);

        $this->assertInstanceOf(SourceData::class, $value);
    }

    public function testGetIndexForFileSource()
    {
        $fileName = 'amplitude.txt';
        $content = "2017 80.9" . PHP_EOL . "2018 70.3";
        $file = vfsStream::newFile($fileName)
            ->withContent($content)
            ->at($this->root);

        $this->register = new Register('file', $file->url(), 'F', 'C', 47.8);

        $this->assertTrue($this->register->getIndex('hromov', 'file', vfsStream::url('rootDir')));
    }

    public function testErrorGetIndexForFileSource()
    {
        $fileName = 'amplitude.txt';
        $content = "2017 80.9" . PHP_EOL . "2018 70.3";
        $file = vfsStream::newFile($fileName)
            ->withContent($content)
            ->at($this->root);

        $this->register = new Register('file', $file->url(), 'F', 'C', 47.8);

        $this->expectException(\Exception::class);
        $this->register->getIndex('indextype', 'file', vfsStream::url('rootDir'));
    }

    public function testGetIndexForArraySource()
    {
        $actual = [
            [2020, 0.78],
            [2021, 0.84],
        ];

        $source = [
            [2020, 18.1],
            [2021, 25.3]
        ];

        $latitude = 47.8;

        $this->register = new Register('array', $source, 'C', 'C', $latitude);

        $this->assertEquals($this->register->getIndex('hromov', 'array'), $actual);
    }

    public function testErrorGetIndexForArraySource()
    {
        $source = [
            [2020, 18.1],
            [2021, 25.3]
        ];

        $latitude = 47.8;

        $this->register = new Register('array', $source, 'C', 'C', $latitude);

        $this->expectException(\Exception::class);
        $this->register->getIndex('hromov', 'cloud');
    }

    public function testGetIndexForOutputJsonSource()
    {
        $actual = '[[2020,0.78],[2021,0.84]]';

        $source = [
            [2020, 18.1],
            [2021, 25.3]
        ];

        $latitude = 47.8;

        $this->register = new Register('array', $source, 'C', 'C', $latitude);

        $this->assertEquals($this->register->getIndex('hromov', 'json'), $actual);
    }

    public function testGetIndexForInputJsonSource()
    {
        $actual = [
            [2020, 0.78],
            [2021, 0.84],
        ];

        $source = '[[2020, 18.1],[2021, 25.3]]';

        $latitude = 47.8;

        $this->register = new Register('json', $source, 'C', 'C', $latitude);

        $this->assertEquals($this->register->getIndex('hromov', 'array'), $actual);
    }

    public function testGetIndexForInputAndOutputJsonSource()
    {
        $actual = '[[2020,0.78],[2021,0.84]]';

        $source = '[[2020, 18.1],[2021, 25.3]]';

        $latitude = 47.8;

        $this->register = new Register('json', $source, 'C', 'C', $latitude);

        $this->assertEquals($this->register->getIndex('hromov', 'json'), $actual);
    }
}