<?php

namespace Soandso\ContinentalIndex;

use Soandso\ContinentalIndex\Data\SourceDataInterface;

/**
 * IndexInterface describes the basic operations for working with the continentality index
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
interface IndexInterface
{
    /**
     * Returns all supported continental index types
     *
     * @return string[]
     */
    public function getIndexTypes() : array;

    /**
     * Returns all output formats of the result of calculating the continentality index
     *
     * @return string[]
     */
    public function getOutputFormats() : array;

    /**
     * Returns the directory for recording the results of calculating the continentality index
     *
     * @return string
     */
    public function getFilePath() : string;

    /**
     * Returns the name of the record file for the results of calculating the continentality index
     *
     * @return string
     */
    public function getFileOutput() : string;

    /**
     * Returns the result of calculating the continence index in the back format, which is in the $source object
     *
     * @param SourceDataInterface $source Class object for working with source data
     * @return mixed
     */
    public function getIndexAssets(SourceDataInterface $source);
}
