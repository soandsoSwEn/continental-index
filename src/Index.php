<?php

namespace Soandso\ContinentalIndex;

use Exception;
use Soandso\ContinentalIndex\Data\SourceDataInterface;

/**
 * Index class contains basic methods for working with continentality indices of different types
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
class Index implements IndexInterface
{
    /**
     * @var string[] Supported Continental Index Types
     */
    private $indices = [
        'hromov' => HromovIndex::class,
    ];

    /**
     * @var string[] Supported output formats
     */
    private array $outputFormats = [
        'file', 'array', 'json',
    ];

    /**
     * @var string directory for recording the results of calculating the continentality index
     */
    private $filePath;

    /**
     * @var string file name of the record file for the results of calculating the continentality index
     */
    private $fileOutput = 'continental_indices.txt';

    /**
     * @var string Output format of the result of calculating the continentality index
     */
    private $format;

    /**
     * @var ContinentalIndexInterface Object of continentality index type
     */
    private $index;

    public function __construct($title, $format, $filePath = null)
    {
        if (array_key_exists($title, $this->indices)) {
            $this->index = new $this->indices[$title];
        } else {
            throw new Exception('Specified continentality index is not supported');
        }

        if (in_array($format, $this->outputFormats)) {
            $this->format = $format;
        } else {
            throw new Exception('The specified output format is not supported');
        }

        if (strcasecmp($format, 'file') == 0 && is_null($filePath)) {
            throw new Exception('The path to save the result is not specified');
        } else {
            $this->filePath = $filePath;
        }
    }

    /**
     * Returns all supported continental index types
     *
     * @return string[] Continental index types
     */
    public function getIndexTypes(): array
    {
        return $this->indices;
    }

    /**
     * Returns all output formats of the result of calculating the continentality index
     *
     * @return string[] Output formats of the result of calculating the continentality index
     */
    public function getOutputFormats(): array
    {
        return $this->outputFormats;
    }

    /**
     * Returns the directory for recording the results of calculating the continentality index
     *
     * @return string the directory for recording the results of continentality index
     */
    public function getFilePath(): string
    {
        return $this->filePath;
    }

    /**
     * Returns the name of the record file for the results of calculating the continentality index
     *
     * @return string File name for the results of continentality index
     */
    public function getFileOutput(): string
    {
        return $this->fileOutput;
    }

    /**
     * Returns the result of calculating the continence index in the back format, which is in the $source object
     *
     * @param SourceDataInterface $source Class object for working with source data
     * @return array|bool|string
     * @throws Exception
     */
    public function getIndexAssets(SourceDataInterface $source)
    {
        $indexAssets = $this->index->getIndexAssets($source->getAssimilateData(), $source->getLatitude());

        if (strcasecmp($this->format, 'file') == 0) {
            return $this->buildFileOutput($indexAssets);
        } elseif (strcasecmp($this->format, 'array') == 0) {
            return $indexAssets;
        } elseif (strcasecmp($this->format, 'json') == 0) {
            return $this->buildJsonOutput($indexAssets);
        } else {
            return false;
        }
    }

    /**
     * Building a file of continentality index results
     *
     * array[year, continentality Index]
     * @param array $indexAssets Continentality Index Data (see above)
     * @return bool
     * @throws Exception
     */
    public function buildFileOutput($indexAssets) : bool
    {
        if (is_dir($this->filePath) === false) {
            throw new Exception('Could not find the specified directory to save the results');
        }

        $fileOutput = fopen($this->filePath . DIRECTORY_SEPARATOR . $this->fileOutput, 'w');
        if ($fileOutput === false) {
            throw new Exception('Failed to open result record file');
        }

        foreach ($indexAssets as $indexItem) {
            $line = $indexItem[0] . ' ' . $indexItem[1] . PHP_EOL;
            fseek($fileOutput, 0, SEEK_END);
            fwrite($fileOutput, $line);
        }

        return fclose($fileOutput);
    }

    /**
     * Returns an array of continentality index data in json format
     *
     * array[year, continentality Index]
     * @param array $indexAssets Continentality Index Data (see above)
     * @return string
     */
    public function buildJsonOutput($indexAssets) : string
    {
        return json_encode($indexAssets);
    }
}
