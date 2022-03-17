<?php

namespace Soandso\ContinentalIndex;

/**
 * ZenkerIndex class contains a method for calculating the continentality index using the Zenker method
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
class ZenkerIndex implements ContinentalIndexInterface
{
    /**
     * Returns the continentality index calculated using the Zenker method
     *
     * @param float $tempAmplitude Temperature amplitude value
     * @param float $latitude The value of geographic latitude
     * @return float Continentality index
     */
    public function calcIndex(float $tempAmplitude, float $latitude): float
    {
        return round((6 / 5 * ($tempAmplitude / sin(deg2rad($latitude)) - 20)), 2);
    }

    /**
     * array[year, temperature amplitude]
     *
     * @param array $tempAmplitudeData Dataset of annual temperature amplitude (see above)
     * @param float $latitude Geographic latitude
     * array[year, continentality Index]
     * @return array Array of Continentality Index Data (see above)
     */
    public function getIndexAssets(array $tempAmplitudeData, float $latitude)
    {
        $indices = [];
        foreach ($tempAmplitudeData as $amplitudeItem) {
            $indices[] = [$amplitudeItem[0], $this->calcIndex($amplitudeItem[1], $latitude)];
        }

        return $indices;
    }
}