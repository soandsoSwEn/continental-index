<?php

namespace Soandso\ContinentalIndex\View;

/**
 * ViewedBuilderInterface must be implemented by the class that plots the continentality index
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
interface ViewedBuilderInterface
{
    /**
     * Plots the continentality index
     */
    public function plotGraph();
}