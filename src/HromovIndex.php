<?php

namespace Soandso\ContinentalIndex;

/**
 * HromovIndex class contains a method for calculating the continentality index using the Hromov method
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
class HromovIndex implements ContinentalIndexInterface
{
    /**
     * Returns the continentality index calculated using the Khromov method
     *
     * @param float $tempAmplitude Temperature amplitude value
     * @param float $latitude The value of geographic latitude
     * @return float Continentality index
     */
    public function calcIndex(float $tempAmplitude, float $latitude) : float
    {
        return round((($tempAmplitude - 5.4 * sin(deg2rad($latitude))) / $tempAmplitude), 2);
    }

    /**
     * array[year, temperature amplitude]
     * @param array $tempAmplitudeData - Dataset of annual temperature amplitude (see above)
     * @param float $latitude Geographic latitude
     * array[year, continentality Index]
     * @return array Array of Continentality Index Data (see above)
     */
    public function getIndexAssets(array $tempAmplitudeData, float $latitude) : array
    {
        $indices = [];
        foreach ($tempAmplitudeData as $amplitudeItem) {
            $indices[] = [$amplitudeItem[0], $this->calcIndex($amplitudeItem[1], $latitude)];
        }

        return $indices;
    }
}
