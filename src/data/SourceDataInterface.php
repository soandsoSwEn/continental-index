<?php

namespace Soandso\ContinentalIndex\Data;

/**
 * Interface SourceDataInterface that must be implemented by the construction class of preparation
 * and generation of initial data
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
interface SourceDataInterface
{
    /**
     * Returns temperature amplitude data
     *
     * Array of returned data:
     * [Year, Amplitude Temperature]
     *
     * Example:
     * [
     *   [1978, 64.9],
     *   [1979, 74],
     *   ..........
     * ]
     *
     * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
     */
    public function getAssimilateData() : array;

    /**
     * Initializes and sets all initial data
     *
     * @param string $inputType Input source type
     * @param array|string $source Input source
     * @param string $inputTempUnits Input temperature units
     * @param string $outputTempUnits Output temperature units
     * @param float $latitude Geographic latitude
     *
     * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
     */
    public function setInitData(string $inputType, $source, string $inputTempUnits, string $outputTempUnits, float $latitude) : void;
}