<?php

namespace Soandso\ContinentalIndex\Data;

/**
 * SourceDataInterface is an interface that must be implemented by the source data processing class
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
interface SourceDataInterface
{
    /**
     * Interface SourceDataInterface that must be implemented by the construction class of preparation
     * and generation of initial data
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
     * @param string $inputType Input source type
     * @param string $source Input source
     * @param string $tempUnits Temperature units
     * @param float $latitude Geographic latitude
     * @return array
     *
     * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
     */
    public function assimilateData(string $inputType, string $source, string $tempUnits, float $latitude) : array;
}