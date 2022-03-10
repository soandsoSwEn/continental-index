<?php

namespace Soandso\ContinentalIndex\Tests\Data;

use bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use Soandso\ContinentalIndex\Data\SourceData;

class SourceDataTest extends TestCase
{
    private $root;

    protected $sourceData;

    protected function setUp() : void
    {
        $this->root = vfsStream::setup('rootDir');
        $this->sourceData = new SourceData('file', '/amplitude.txt', 'C', 'C', 45.5);
    }

    protected function tearDown() : void
    {
        unset($this->sourceData);
    }

    public function testSuccessSetInputArrayType()
    {
        $this->sourceData->setInputType('array');
        $this->assertEquals('array', $this->sourceData->getInputType());
    }

    public function testSuccessSetInputFileType()
    {
        $this->sourceData->setInputType('file');
        $this->assertEquals('file', $this->sourceData->getInputType());
    }

    public function testSuccessSetInputJsonType()
    {
        $this->sourceData->setInputType('json');
        $this->assertEquals('json', $this->sourceData->getInputType());
    }

    public function testExceptionSetInputType()
    {
        $this->expectException(\Exception::class);

        $this->sourceData->setInputType('file1');
    }

    public function testSuccessCheckArraySource()
    {
        $this->sourceData->setInputType('array');
        $reflector = new \ReflectionClass(SourceData::class);

        $method = $reflector->getMethod('checkSource');
        $method->setAccessible(true);
        $source = [
            [2020, 18.1],
            [2021, 25.3]
        ];
        $result = $method->invokeArgs($this->sourceData, [$source]);

        $this->assertTrue($result);
    }

    public function testSuccessCheckFileSource()
    {
        $this->sourceData->setInputType('file');
        $reflector = new \ReflectionClass(SourceData::class);

        $method = $reflector->getMethod('checkSource');
        $method->setAccessible(true);
        $result = $method->invokeArgs($this->sourceData, ['/amplitude.txt']);

        $this->assertTrue($result);
    }

    public function testSuccessCheckJsonSource()
    {
        $this->sourceData->setInputType('json');
        $reflector = new \ReflectionClass(SourceData::class);

        $method = $reflector->getMethod('checkSource');
        $method->setAccessible(true);
        $source = [
            [2020, 18.1],
            [2021, 25.3]
        ];
        $source = json_encode($source);
        $result = $method->invokeArgs($this->sourceData, [$source]);

        $this->assertTrue($result);
    }

    public function testErrorCheckArraySource()
    {
        $this->sourceData->setInputType('array');
        $reflector = new \ReflectionClass(SourceData::class);

        $method = $reflector->getMethod('checkSource');
        $method->setAccessible(true);
        $source = 'array';
        $result = $method->invokeArgs($this->sourceData, [$source]);

        $this->assertFalse($result);
    }

    public function testErrorCheckFileSource()
    {
        $this->sourceData->setInputType('file');
        $reflector = new \ReflectionClass(SourceData::class);

        $method = $reflector->getMethod('checkSource');
        $method->setAccessible(true);
        $source = [
            [2020, 18.1],
            [2021, 25.3]
        ];
        $result = $method->invokeArgs($this->sourceData, [$source]);

        $this->assertFalse($result);
    }

    public function testErrorCheckJsonSource()
    {
        $this->sourceData->setInputType('json');
        $reflector = new \ReflectionClass(SourceData::class);

        $method = $reflector->getMethod('checkSource');
        $method->setAccessible(true);
        $source = [
            [2020, 18.1],
            [2021, 25.3]
        ];
        $result = $method->invokeArgs($this->sourceData, [$source]);

        $this->assertFalse($result);
    }

    public function testSuccessSetFileSourceType()
    {
        $this->sourceData->setInputType('file');
        $this->sourceData->setSource('/outputfile.txt');
        $this->assertEquals('/outputfile.txt', $this->sourceData->getSource());
    }

    public function testSuccessSetArraySourceType()
    {
        $source = [
            [2020, 18.1],
            [2021, 25.3]
        ];

        $this->sourceData->setInputType('array');
        $this->sourceData->setSource($source);
        $this->assertEquals($source, $this->sourceData->getSource());
    }

    public function testSuccessSetJsonSourceType()
    {
        $source = [
            [2020, 18.1],
            [2021, 25.3]
        ];

        $source = json_encode($source);

        $this->sourceData->setInputType('json');
        $this->sourceData->setSource($source);
        $this->assertEquals($source, $this->sourceData->getSource());
    }

    public function testExceptionSetArraySourceType()
    {
        $this->expectException(\Exception::class);
        $this->sourceData->setInputType('array');
        $this->sourceData->setSource('test.txt');
    }

    public function testExceptionSetFileSourceType()
    {
        $this->expectException(\Exception::class);
        $this->sourceData->setInputType('file');
        $source = [
            [2020, 18.1],
            [2021, 25.3]
        ];

        $this->sourceData->setSource($source);
    }

    public function testExceptionSetJsonSourceType()
    {
        $this->expectException(\Exception::class);
        $this->sourceData->setInputType('json');
        $source = [
            [2020, 18.1],
            [2021, 25.3]
        ];

        $this->sourceData->setSource($source);
    }

    public function testSuccessCheckTempUnitsOfC()
    {
        $reflector = new \ReflectionClass(SourceData::class);

        $method = $reflector->getMethod('checkTempUnits');
        $method->setAccessible(true);
        $result = $method->invokeArgs($this->sourceData, ['C']);

        $this->assertTrue($result);
    }

    public function testSuccessCheckTempUnitsOfF()
    {
        $reflector = new \ReflectionClass(SourceData::class);

        $method = $reflector->getMethod('checkTempUnits');
        $method->setAccessible(true);
        $result = $method->invokeArgs($this->sourceData, ['F']);

        $this->assertTrue($result);
    }

    public function testErrorCheckTempUnits()
    {
        $reflector = new \ReflectionClass(SourceData::class);

        $method = $reflector->getMethod('checkTempUnits');
        $method->setAccessible(true);
        $result = $method->invokeArgs($this->sourceData, ['C2323']);

        $this->assertFalse($result);
    }

    public function testSuccessSetInputTempUnits()
    {
        $this->sourceData->setTempUnits('F', 'C');
        $this->assertEquals('F', $this->sourceData->getInputTempUnits());
    }

    public function testErrorSetInputTempUnits()
    {
        $this->sourceData->setTempUnits('F', 'C');
        $this->assertNotEquals('C', $this->sourceData->getInputTempUnits());
    }

    public function testSuccessSetOutputTempUnits()
    {
        $this->sourceData->setTempUnits('F', 'C');
        $this->assertEquals('C', $this->sourceData->getOutputTempUnits());
    }

    public function testErrorSetOutputTempUnits()
    {
        $this->sourceData->setTempUnits('F', 'C');
        $this->assertNotEquals('F', $this->sourceData->getOutputTempUnits());
    }

    public function testSuccessSetTempUnits()
    {
        $this->sourceData->setTempUnits('F', 'C');
        $this->assertEquals('F', $this->sourceData->getInputTempUnits());
        $this->assertEquals('C', $this->sourceData->getOutputTempUnits());
    }

    public function testErrorSetTempUnits()
    {
        $this->sourceData->setTempUnits('F', 'C');
        $this->assertNotEquals('C', $this->sourceData->getInputTempUnits());
        $this->assertNotEquals('F', $this->sourceData->getOutputTempUnits());
    }

    public function testExceptionSetTempUnits()
    {
        $this->expectException(\Exception::class);
        $this->sourceData->setTempUnits('A', 'C');
    }

    public function testSuccessCheckLatitude()
    {
        $this->assertTrue($this->sourceData->checkLatitude(20.5));
    }

    public function testErrorOfMinCheckLatitude()
    {
        $this->assertFalse($this->sourceData->checkLatitude(-5.0));
    }

    public function testErrorOfMaxCheckLatitude()
    {
        $this->assertFalse($this->sourceData->checkLatitude(90.5));
    }

    public function testSuccessSetLatitude()
    {
        $this->sourceData->setLatitude(45.8);
        $this->assertEquals(45.8, $this->sourceData->getLatitude());
    }

    public function testErrorSetLatitude()
    {
        $this->sourceData->setLatitude(45.8);
        $this->assertNotEquals(45.5, $this->sourceData->getLatitude());
    }

    public function testExceptionSetLatitude()
    {
        $this->expectException(\Exception::class);
        $this->sourceData->setLatitude(120.0);
    }

    public function testIsFile()
    {
        $this->sourceData->setInputType('file');
        $fileName = 'amplitude.txt';
        $content = "2017 80.9" . PHP_EOL . "2018 70.3";
        //$this->sourceData->setSource('/outputfile.txt');
        /*$filePath = mkdir(vfsStream::url('rootDir/' . $fileName));
        $this->sourceData->setSource(vfsStream::url('rootDir/' . $fileName));
        $this->assertTrue($this->root->hasChild($fileName));*/

        $file = vfsStream::newFile($fileName)
            ->withContent($content)
            ->at($this->root);

        $this->assertStringEqualsFile($file->url(), $content);
    }

    public function testGetSourceFromNotEmptyFile()
    {
        $this->sourceData->setInputType('file');

        $fileName = 'amplitude.txt';
        $content = "2017 80.9" . PHP_EOL . "2018 70.3";
        $file = vfsStream::newFile($fileName)
            ->withContent($content)
            ->at($this->root);

        $this->sourceData->setSource($file->url());

        $actual = [
            [2017, 80.9],
            [2018, 70.3],
        ];

        $reflector = new \ReflectionClass(SourceData::class);

        $method = $reflector->getMethod('getSourceFromFile');
        $method->setAccessible(true);
        $result = $method->invoke($this->sourceData);

        $this->assertEquals($result, $actual);
    }

    public function testGetSourceFromEmptyFile()
    {
        $this->sourceData->setInputType('file');

        $fileName = 'amplitude.txt';
        $content = '';
        $file = vfsStream::newFile($fileName)
            ->withContent($content)
            ->at($this->root);

        $this->sourceData->setSource($file->url());

        $reflector = new \ReflectionClass(SourceData::class);

        $method = $reflector->getMethod('getSourceFromFile');
        $method->setAccessible(true);
        $result = $method->invoke($this->sourceData);

        $this->assertFalse($result);
    }

    public function testGetSourceFromErrorFile()
    {
        $this->sourceData->setInputType('file');

        $fileName = 'amplitude.txt';
        $content = "2017 80.9" . PHP_EOL . "2018 70.3";
        $file = vfsStream::newFile($fileName)
            ->withContent($content)
            ->at($this->root);

        $this->sourceData->setSource('amplitude');

        $actual = [
            [2017, 80.9],
            [2018, 70.3],
        ];

        $this->expectException(\Exception::class);
        $reflector = new \ReflectionClass(SourceData::class);

        $method = $reflector->getMethod('getSourceFromFile');
        $method->setAccessible(false);
        $result = $method->invoke($this->sourceData);

        $this->assertEquals($result, $actual);
    }

    public function testGetSourceFromNotEmptyArray()
    {
        $this->sourceData->setInputType('array');

        $actual = [
            [2017, 80.9],
            [2018, 70.3],
        ];

        $this->sourceData->setSource($actual);

        $reflector = new \ReflectionClass(SourceData::class);
        $method = $reflector->getMethod('getSourceFromArray');
        $method->setAccessible(true);
        $result = $method->invoke($this->sourceData);

        $this->assertEquals($result, $actual);
    }

    public function testGetSourceFromEmptyArray()
    {
        $this->sourceData->setInputType('array');

        $actual = [];

        $this->sourceData->setSource($actual);

        $reflector = new \ReflectionClass(SourceData::class);
        $method = $reflector->getMethod('getSourceFromArray');
        $method->setAccessible(true);
        $result = $method->invoke($this->sourceData);

        $this->assertFalse($result);
    }

    public function testGetSourceFromNotEmptyJson()
    {
        $this->sourceData->setInputType('json');

        $actual = [
            [2017, 80.9],
            [2018, 70.3],
        ];

        $this->sourceData->setSource(json_encode($actual));

        $reflector = new \ReflectionClass(SourceData::class);
        $method = $reflector->getMethod('getSourceFromJson');
        $method->setAccessible(true);
        $result = $method->invoke($this->sourceData);

        $this->assertEquals($result, $actual);
    }

    public function testGetSourceFromEmptyJson()
    {
        $this->sourceData->setInputType('json');

        $actual = [];

        $this->sourceData->setSource(json_encode($actual));

        $reflector = new \ReflectionClass(SourceData::class);
        $method = $reflector->getMethod('getSourceFromJson');
        $method->setAccessible(true);
        $result = $method->invoke($this->sourceData);

        $this->assertFalse($result);
    }

    public function testGetTempAmplitudeDataFromNotEmptyFile()
    {
        $this->sourceData->setInputType('file');

        $fileName = 'amplitude.txt';
        $content = "2017 80.9" . PHP_EOL . "2018 70.3";
        $file = vfsStream::newFile($fileName)
            ->withContent($content)
            ->at($this->root);

        $this->sourceData->setSource($file->url());

        $actual = [
            [2017, 80.9],
            [2018, 70.3],
        ];

        $reflector = new \ReflectionClass(SourceData::class);

        $method = $reflector->getMethod('getTempAmplitudeData');
        $method->setAccessible(true);
        $result = $method->invoke($this->sourceData);

        $this->assertEquals($result, $actual);
    }

    public function testGetTempAmplitudeDataFromEmptyFile()
    {
        $this->sourceData->setInputType('file');

        $fileName = 'amplitude.txt';
        $content = '';
        $file = vfsStream::newFile($fileName)
            ->withContent($content)
            ->at($this->root);

        $this->sourceData->setSource($file->url());

        $reflector = new \ReflectionClass(SourceData::class);

        $method = $reflector->getMethod('getTempAmplitudeData');
        $method->setAccessible(true);
        $result = $method->invoke($this->sourceData);

        $this->assertFalse($result);
    }

    public function testGetTempAmplitudeDataFromNotEmptyArray()
    {
        $this->sourceData->setInputType('array');

        $actual = [
            [2017, 80.9],
            [2018, 70.3],
        ];

        $this->sourceData->setSource($actual);

        $reflector = new \ReflectionClass(SourceData::class);
        $method = $reflector->getMethod('getTempAmplitudeData');
        $method->setAccessible(true);
        $result = $method->invoke($this->sourceData);

        $this->assertEquals($result, $actual);
    }

    public function testGetTempAmplitudeDataFromEmptyArray()
    {
        $this->sourceData->setInputType('array');

        $actual = [];

        $this->sourceData->setSource($actual);

        $reflector = new \ReflectionClass(SourceData::class);
        $method = $reflector->getMethod('getTempAmplitudeData');
        $method->setAccessible(true);
        $result = $method->invoke($this->sourceData);

        $this->assertFalse($result);
    }

    public function testGetTempAmplitudeDataFromNotEmptyJson()
    {
        $this->sourceData->setInputType('json');

        $actual = [
            [2017, 80.9],
            [2018, 70.3],
        ];

        $this->sourceData->setSource(json_encode($actual));

        $reflector = new \ReflectionClass(SourceData::class);
        $method = $reflector->getMethod('getTempAmplitudeData');
        $method->setAccessible(true);
        $result = $method->invoke($this->sourceData);

        $this->assertEquals($result, $actual);
    }

    public function testGetTempAmplitudeDataFromEmptyJson()
    {
        $this->sourceData->setInputType('json');

        $actual = [];

        $this->sourceData->setSource(json_encode($actual));

        $reflector = new \ReflectionClass(SourceData::class);
        $method = $reflector->getMethod('getTempAmplitudeData');
        $method->setAccessible(true);
        $result = $method->invoke($this->sourceData);

        $this->assertFalse($result);
    }

    public function testSuccessGetAssimilateDataFromFile()
    {
        $this->sourceData->setInputType('file');

        $fileName = 'amplitude.txt';
        $content = "2017 80.9" . PHP_EOL . "2018 70.3";
        $file = vfsStream::newFile($fileName)
            ->withContent($content)
            ->at($this->root);

        $this->sourceData->setSource($file->url());

        $actual = [
            [2017, 80.9],
            [2018, 70.3],
        ];

        $this->assertEquals($this->sourceData->getAssimilateData(), $actual);
    }

    public function testErrorGetAssimilateDataFromFile()
    {
        $this->sourceData->setInputType('file');

        $fileName = 'amplitude.txt';
        $content = '';
        $file = vfsStream::newFile($fileName)
            ->withContent($content)
            ->at($this->root);

        $this->sourceData->setSource($file->url());

        $this->expectException(\Exception::class);
        $this->sourceData->getAssimilateData();
    }

    public function testSuccessGetAssimilateDataFromArray()
    {
        $this->sourceData->setInputType('array');

        $actual = [
            [2017, 80.9],
            [2018, 70.3],
        ];

        $this->sourceData->setSource($actual);

        $this->assertEquals($this->sourceData->getAssimilateData(), $actual);
    }

    public function testErrorGetAssimilateDataFromArray()
    {
        $this->sourceData->setInputType('array');

        $actual = [];

        $this->sourceData->setSource($actual);

        $this->expectException(\Exception::class);
        $this->sourceData->getAssimilateData();
    }

    public function testSuccessGetAssimilateDataFromJson()
    {
        $this->sourceData->setInputType('json');

        $actual = [
            [2017, 80.9],
            [2018, 70.3],
        ];

        $this->sourceData->setSource(json_encode($actual));

        $this->assertEquals($this->sourceData->getAssimilateData(), $actual);
    }

    public function testErrorGetAssimilateDataFromJson()
    {
        $this->sourceData->setInputType('json');

        $actual = [];

        $this->sourceData->setSource(json_encode($actual));

        $this->expectException(\Exception::class);
        $this->sourceData->getAssimilateData();
    }

    public function testSuccessCheckInputTypeOfFile()
    {
        $reflector = new \ReflectionClass(SourceData::class);
        $method = $reflector->getMethod('checkInputType');
        $method->setAccessible(true);
        $result = $method->invokeArgs($this->sourceData, ['file']);

        $this->assertTrue($result);
    }

    public function testSuccessCheckInputTypeOfArray()
    {
        $reflector = new \ReflectionClass(SourceData::class);
        $method = $reflector->getMethod('checkInputType');
        $method->setAccessible(true);
        $result = $method->invokeArgs($this->sourceData, ['array']);

        $this->assertTrue($result);
    }

    public function testSuccessCheckInputTypeOfJson()
    {
        $reflector = new \ReflectionClass(SourceData::class);
        $method = $reflector->getMethod('checkInputType');
        $method->setAccessible(true);
        $result = $method->invokeArgs($this->sourceData, ['json']);

        $this->assertTrue($result);
    }

    public function testErrorCheckInputTypeOfJson()
    {
        $reflector = new \ReflectionClass(SourceData::class);
        $method = $reflector->getMethod('checkInputType');
        $method->setAccessible(true);
        $result = $method->invokeArgs($this->sourceData, ['object']);

        $this->assertFalse($result);
    }

    public function testSuccessConvertFromFtoC()
    {
        $actual = 27.17;

        $reflector = new \ReflectionClass(SourceData::class);
        $method = $reflector->getMethod('convertFromFtoC');
        $method->setAccessible(true);
        $result = $method->invokeArgs($this->sourceData, [80.9]);

        $this->assertEquals($result, $actual);
    }

    public function testErrorConvertFromFtoC()
    {
        $actual = 27.5;

        $reflector = new \ReflectionClass(SourceData::class);
        $method = $reflector->getMethod('convertFromFtoC');
        $method->setAccessible(true);
        $result = $method->invokeArgs($this->sourceData, [80.9]);

        $this->assertNotEquals($result, $actual);
    }

    public function testSuccessConvertFromCtoF()
    {
        $actual = 80.96;

        $reflector = new \ReflectionClass(SourceData::class);
        $method = $reflector->getMethod('convertFromCtoF');
        $method->setAccessible(true);
        $result = $method->invokeArgs($this->sourceData, [27.2]);

        $this->assertEquals($result, $actual);
    }

    public function testErrorConvertFromCtoF()
    {
        $actual = 80.1;

        $reflector = new \ReflectionClass(SourceData::class);
        $method = $reflector->getMethod('convertFromCtoF');
        $method->setAccessible(true);
        $result = $method->invokeArgs($this->sourceData, [27.2]);

        $this->assertNotEquals($result, $actual);
    }

    public function testSuccessConvertTemperatureEqualUnits()
    {
        $actual = 27.2;

        $reflector = new \ReflectionClass(SourceData::class);
        $method = $reflector->getMethod('convertTemperature');
        $method->setAccessible(true);
        $result = $method->invokeArgs($this->sourceData, ['C', 'C', 27.2]);

        $this->assertEquals($result, $actual);
    }

    public function testSuccessConvertTemperatureForFToC()
    {
        $actual = 27.2;

        $reflector = new \ReflectionClass(SourceData::class);
        $method = $reflector->getMethod('convertTemperature');
        $method->setAccessible(true);
        $result = $method->invokeArgs($this->sourceData, ['F', 'C', 80.96]);

        $this->assertEquals($result, $actual);
    }

    public function testSuccessConvertTemperatureForCToF()
    {
        $actual = 80.96;

        $reflector = new \ReflectionClass(SourceData::class);
        $method = $reflector->getMethod('convertTemperature');
        $method->setAccessible(true);
        $result = $method->invokeArgs($this->sourceData, ['C', 'F', 27.2]);

        $this->assertEquals($result, $actual);
    }

    public function testErrorConvertTemperature()
    {
        $this->expectException(\Exception::class);
        $reflector = new \ReflectionClass(SourceData::class);
        $method = $reflector->getMethod('convertTemperature');
        $method->setAccessible(true);
        $method->invokeArgs($this->sourceData, ['K', 'F', 27.2]);
    }
}