<?php

namespace Soandso\ContinentalIndex;

use Exception;
use Soandso\ContinentalIndex\Data\SourceData;
use Soandso\ContinentalIndex\Data\SourceDataInterface;

/**
 * Class Register is the core class for the component application
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
class Register
{
    /**
     * Hromov index title
     */
    const HROMOV_INDEX = 'hromov';

    /**
     * Gorchinsky index title
     */
    const GORCHINSKY_INDEX = 'gorchinsky';

    /**
     * Conrad index title
     */
    const CONRAD_INDEX = 'conrad';

    /**
     * Zenker index title
     */
    const ZENKER_INDEX = 'zenker';

    /**
     * Fahrenheit title
     */
    const FAHRENHEIT = 'F';

    /**
     * Celsius title
     */
    const CELSIUS = 'C';

    /**
     * File format title
     */
    const FILE = 'file';

    /**
     * Array format title
     */
    const ARRAY = 'array';

    /**
     * Json format title
     */
    const JSON = 'json';

    /**
     * @var SourceDataInterface object for working with source data
     */
    private $sourceData;

    public function __construct(string $inputType, $source, string $inputTempUnits, string $outputTempUnits, float $latitude)
    {
        $this->setData($inputType, $source, $inputTempUnits, $outputTempUnits, $latitude);
    }

    /**
     * Initiates start-up data for the calculation of the continental index
     *
     * @param string $inputType Input source type
     * @param string|array $source Input source
     * @param string $inputTempUnits Input temperature amplitude units
     * @param string $outputTempUnits Output temperature amplitude units
     * @param float $latitude Location latitude
     */
    protected function setData(string $inputType, $source, string $inputTempUnits, string $outputTempUnits, float $latitude)
    {
        $this->sourceData = new SourceData($inputType, $source, $inputTempUnits, $outputTempUnits, $latitude);
    }

    /**
     * Returns the result of the calculation of the continental index
     *
     * @param string $title Type index of continentality
     * @param string $format Output format
     * @param string|null $filePath The path to the directory for extracting the results file
     * @return array|bool|string
     * @throws Exception
     */
    public function getIndex(string $title, string $format, string $filePath = null)
    {
        $contitnetalIndex = new Index($title, $format, $filePath);
        return $contitnetalIndex->getIndexAssets($this->sourceData);
    }
}
