<?php

namespace Soandso\ContinentalIndex\Data;

use Exception;

/**
 * Class for working with source data
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
class SourceData implements SourceDataInterface
{
    /**
     * @var string Input source type
     */
    private string $inputType;

    /**
     * @var string|array Input source
     */
    private $source;

    /**
     * @var string Input temperature units
     */
    private string $inputTempUnits;

    /**
     * @var string Output temperature units
     */
    private string $outputTempUnits;

    /**
     * @var float Geographic latitude
     */
    private float $latitude;

    /**
     * @var string[] Supported types of source data
     */
    private array $inputTypes = [
        'file', 'array', 'json',
    ];

    /**
     * @var string[] Supported temperature unit options
     * F (Fahrenheit), C (Degree Celsius)
     */
    private array $tempUnitOptions = [
        'F', 'C',
    ];

    public function __construct(string $inputType, $source, string $inputTempUnits, string $outputTempUnits, float $latitude)
    {
        $this->setInitData($inputType, $source, $inputTempUnits, $outputTempUnits, $latitude);
    }

    /**
     * Returns Input source type
     *
     * @return string Input source type
     */
    public function getInputType() : string
    {
        return $this->inputType;
    }

    /**
     * Returns input source
     *
     * @return string|array Input source
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Returns temperature units
     *
     * @return string Input temperature units
     */
    public function getInputTempUnits() : string
    {
        return $this->inputTempUnits;
    }

    /**
     * @return string Output temperature units
     */
    public function getOutputTempUnits() : string
    {
        return $this->outputTempUnits;
    }

    /**
     * Returns geographic latitude
     *
     * @return float Geographic latitude
     */
    public function getLatitude() : float
    {
        return $this->latitude;
    }

    /**
     * Sets Input source type
     *
     * @param string $inputType Input source type
     * @throws Exception
     */
    public function setInputType(string $inputType) : void
    {
        if ($this->checkInputType($inputType)) {
            $this->inputType = $inputType;
        } else {
            throw new Exception('Data source type is not correct');
        }
    }

    /**
     * @param string|array $source Input source
     * @throws Exception
     */
    public function setSource($source) : void
    {
        if ($this->checkSource($source)) {
            $this->source = $source;
        } else {
            throw new Exception('Data source entered incorrectly');
        }
    }

    /**
     * Sets temperature units
     *
     * @param string $inputTempUnits Input temperature units
     * @throws Exception
     */
    public function setTempUnits(string $inputTempUnits, string $outputTempUnits) : void
    {
        if ($this->checkTempUnits($inputTempUnits)) {
            $this->inputTempUnits = $inputTempUnits;
        } else {
            throw new Exception('Input temperature units are incorrectly entered');
        }

        if ($this->checkTempUnits($outputTempUnits)) {
            $this->outputTempUnits = $outputTempUnits;
        } else {
            throw new Exception('Output temperature units are incorrectly entered');
        }
    }

    /**
     * Sets geographic latitude
     *
     * @param float $latitude Geographic latitude
     * @throws Exception
     */
    public function setLatitude(float $latitude) : void
    {
        if ($this->checkLatitude($latitude)) {
            $this->latitude = $latitude;
        } else {
            throw new Exception('Geographic latitude entered incorrectly');
        }
    }

    /**
     * Returns temperature amplitude data
     *
     * Assimilates the initial data, returns the prepared data to the calculation of the continentality index
     *
     * @return array
     * @throws Exception
     */
    public function getAssimilateData() : array
    {
        $TempAmplitude = $this->getTempAmplitudeData();
        if ($TempAmplitude === false) {
            throw new Exception();
        }

        return $TempAmplitude;
    }

    /**
     * Returns the result of checking the validity of the type of the source data
     *
     * @param string $inputType Input data source type
     * @return bool
     */
    protected function checkInputType(string $inputType) : bool
    {
        return in_array($inputType, $this->inputTypes);
    }

    /**
     * Returns the result of checking the validity of the data source
     *
     * @param string|array $source Input source
     * @return bool
     */
    protected function checkSource($source) : bool
    {
        if (strcasecmp($this->getInputType(), 'array') == 0) {
            return is_array($source);
        } else {
            return is_string($source);
        }
    }

    /**
     * Returns the result of checking the validity of the Temperature amplitude units
     *
     * @param string $tempUnits Temperature amplitude units
     * @return bool
     */
    protected function checkTempUnits(string $tempUnits) : bool
    {
        return in_array($tempUnits, $this->tempUnitOptions);
    }

    /**
     * Returns the result of checking the validity of the location latitude
     *
     * @param float $latitude Location latitude
     * @return bool
     */
    public function checkLatitude(float $latitude) : bool
    {
        return $latitude >=0 && $latitude <= 90;
    }

    /**
     * Initializes and sets all initial data
     *
     * @param string $inputType Input data source type - 'file', 'array', 'json'
     *
     * @param array|string $source Incoming annual amplitude data
     * Source data structure
     * file:
     * Year, space, temperature amplitude value
     * Example:
     * 2022 78.5
     * .........
     *
     * array:
     * [
     *  Year, temperature amplitude value
     * ]
     * Example:
     * [
     *  [2017, 80.9],
     *  [2018, 70.3],
     *  ............
     * ]
     *
     * json:
     * This format is an array (see above) encoded into a json string
     *
     * @param string $inputTempUnits Input temperature amplitude units - 'F' (Fahrenheit) or 'C' (Degree Celsius)
     * @param string $outputTempUnits Output temperature amplitude units - 'F' (Fahrenheit) or 'C' (Degree Celsius)
     * @param float $latitude Location latitude
     * @return void
     * @throws Exception
     */
    public function setInitData(string $inputType, $source, string $inputTempUnits, string $outputTempUnits, float $latitude) : void
    {
        $this->setInputType($inputType);
        $this->setSource($source);
        $this->setTempUnits($inputTempUnits, $outputTempUnits);
        $this->setLatitude($latitude);
    }

    /**
     * Returns prepared annual air temperature amplitude data
     *
     * @return array|false|mixed|string
     * @throws Exception
     */
    public function getTempAmplitudeData()
    {
        if (strcasecmp($this->getInputType(), 'file') == 0) {
            return $this->getSourceFromFile();
        } elseif (strcasecmp($this->getInputType(), 'array') == 0) {
            return $this->getSourceFromArray();
        } elseif (strcasecmp($this->getInputType(), 'json') == 0) {
            return $this->getSourceFromJson();
        } else {
            return false;
        }
    }

    /**
     * Returns the prepared data of the annual air temperature amplitude from the source file type
     *
     * @return array|false
     */
    protected function getSourceFromFile()
    {
        $inputFile = fopen($this->getSource(), 'r');

        $output = [];
        while (!feof($inputFile)) {
            $line = fgets($inputFile);
            if ($line === false) {
                continue;
            }

            $sourceItem = explode(' ', $line);
            $output[] = [intval($sourceItem[0]), $this->convertTemperature($this->inputTempUnits, $this->outputTempUnits, $sourceItem[1])];
        }

        fclose($inputFile);

        if (count($output) > 0) {
            return $output;
        } else {
            return false;
        }
    }

    /**
     * Returns prepared annual air temperature amplitude data from array source type
     *
     * @return array|false Air temperature amplitude data
     * @throws Exception
     */
    protected function getSourceFromArray()
    {
        if (count($this->getSource()) == 0) {
            return false;
        }

        $output =  [];

        foreach ($this->getSource() as $item) {
            $output = [intval($item[0]), $this->convertTemperature($this->inputTempUnits, $this->outputTempUnits, $item[1])];
        }

        if (count($output) > 0) {
            return $output;
        } else {
            return false;
        }
    }

    /**
     * Returns prepared annual air temperature amplitude data from json source type
     *
     * @return mixed
     */
    protected function getSourceFromJson()
    {
        $output =  json_decode($this->getSource());

        if (count($output) > 0) {
            return $output;
        } else {
            return false;
        }
    }

    /**
     * Returns the temperature value in the selected unit of measurement
     *
     * Converts temperature values from one unit of measure to another
     *
     * @param string $unitFrom Input temperature units
     * @param string $unitTo Output temperature units
     * @param float $value Temperature value
     * @return float
     * @throws Exception
     */
    protected function convertTemperature(string $unitFrom, string $unitTo, float $value) : float
    {
        if (strcasecmp($unitFrom, $unitTo) == 0) {
            return round($value, 2);
        }

        if (strcasecmp($unitFrom, 'F') == 0 && strcasecmp($unitTo, 'C') == 0) {
            return $this->convertFromFtoC($value);
        } elseif (strcasecmp($unitFrom, 'C') == 0 && strcasecmp($unitTo, 'F') == 0) {
            return $this->convertFromCtoF($value);
        } else {
            throw new Exception('Temperature units are incorrectly entered');
        }
    }

    /**
     * Returns the temperature value in degrees Celsius
     *
     * Converts temperature values in degrees Fahrenheit to values in degrees Celsius
     *
     * @param float $value Temperature value in degrees Fahrenheit
     * @return float Temperature value in degrees Celsius
     */
    private function convertFromFtoC(float $value) : float
    {
        return round((($value - 32) / 1.8), 2);
    }

    /**
     * Returns the temperature value in degrees Fahrenheit
     *
     * Converts temperature values in degrees Celsius to degrees Fahrenheit
     *
     * @param float $value Temperature value in degrees Celsius
     * @return float Temperature value in degrees Fahrenheit
     */
    private function convertFromCtoF(float $value) : float
    {
        return round((($value * 1.8) + 32), 2);
    }
}
