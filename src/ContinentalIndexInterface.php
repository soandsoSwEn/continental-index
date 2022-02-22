<?php

namespace Soandso\ContinentalIndex;

/**
 * ContinentalIndexInterface must be implemented by a class that calculates the value of a continental index
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
interface ContinentalIndexInterface
{
    /**
     * Returns the value of the continentality index
     *
     * @param float $tempAmplitude Temperature amplitude value
     * @param float $longitude The value of geographic latitude
     * @return float
     */
    public function calcIndex(float $tempAmplitude, float $longitude) : float;
}