<?php

namespace Soandso\ContinentalIndex;

/**
 * HromovIndex class contains a method for calculating the continentality index using the Gorchinsky method
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
class GorchinskyIndex implements ContinentalIndexInterface
{
    /**
     * Returns the continentality index calculated using the Gorchinsky method
     *
     * @param float $tempAmplitude Temperature amplitude value
     * @param float $latitude The value of geographic latitude
     * @return float Continentality index
     */
    public function calcIndex(float $tempAmplitude, float $latitude): float
    {
        return round(((1.7 * $tempAmplitude) / sin(deg2rad($latitude))), 2);
    }

    /**
     * array[year, temperature amplitude]
     * @param array $tempAmplitudeData - Dataset of annual temperature amplitude (see above)
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