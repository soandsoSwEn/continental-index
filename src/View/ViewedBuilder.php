<?php

namespace Soandso\ContinentalIndex\View;

use Exception;

/**
 * ViewedBuilder class contains methods for plotting the continentality index
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
class ViewedBuilder implements ViewedBuilderInterface
{
    /**
     * @var string Path to source directory
     */
    private string $sourcePath;

    /**
     * @var string Data set prepared for use in graphics
     */
    private string $assetsOutput;

    /**
     * @var string URI of the directory with plotting library files
     */
    private string $assetsUri;

    /**
     * @var array User options for plotting
     */
    private array $options = [];

    /**
     * @var array Graphing Providers
     */
    private array $graphingProviders = [
        'dygraph',
    ];

    /**
     * @var array Default Plot Settings
     */
    private array $graphSettings = [
        /** Plot provider **/
        'provider'   => 'dygraph',
        /** Show or hide the range selector widget (Only for Dygraph provider) **/
        'showRangeSelector' => null,
        /** Height, in pixels, of the range selector widget (Only for Dygraph provider) **/
        'rangeSelectorHeight' => null,
        /** Width, in pixels, of the chart **/
        'width'      => 480,
        /** Height, in pixels, of the chart **/
        'height'     => 320,
        /** Text to display above the chart **/
        'title'      => 'Continental index',
        /** Text to display to the left of the chart's y-axis **/
        'ylabel'     => 'Index',
        /** Text to display below the chart's x-axis **/
        'xlabel'     => 'Date',
    ];

    private array $customOptions = [];

    public function __construct(array $indexAssets, array $options = null)
    {
        $this->sourcePath = dirname(__DIR__);
        $this->setSettings($options);
        $this->assetsOutput = $this->buildGraphData($indexAssets, $this->getGraphProvider());
        $this->assetsUri = $this->getAssetUri();
    }

    /**
     * Returns the plotting provider
     *
     * @return string Plotting provider
     */
    public function getGraphProvider()
    {
        return $this->graphSettings['provider'];
    }

    public function getGraphOptions(): string
    {
        unset($this->customOptions['provider']);
        foreach ($this->customOptions as $key => $option) {
            if (is_null($option)) {
                unset($this->customOptions[$key]);
            }
        }

        return json_encode($this->customOptions);
    }

    public function setSettings(array $options = null): void
    {
        if (is_null($options)) {
            $this->customOptions = $this->graphSettings;
        } else {
            $diffOptions = array_diff_key($options, $this->graphSettings);
            if (count($diffOptions) > 0) {
                foreach ($diffOptions as $key => $valueNotOption) {
                    unset($options[$key]);
                }
            }

            $this->customOptions = array_replace($this->graphSettings, $options);
        }
    }

    /**
     * Builds a graph of the continentality index
     * @throws Exception
     */
    public function plotGraph()
    {
        if ($this->assetsOutput === false) {
            throw new Exception('Data generation error for plotting');
        }

        $this->buildTemplate($this->assetsOutput, $this->assetsUri, $this->getGraphProvider(), $this->getGraphOptions());
    }

    /**
     * Renders the specified continentality index plot type
     *
     * @param string $assetsOutput Data set prepared for use in graphics
     * @param string $assetsUri URI of directory with plotting library
     * @param string $provider Plotting provider
     */
    public function buildTemplate(string $assetsOutput, string $assetsUri, string $provider, string $graphOptions)
    {
        require $this->sourcePath . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR  . $provider.'.php';
    }

    /**
     * Builds a set of initial data for the chart
     *
     * array[year, continentality Index]
     * @param $indexAssets array Continental index data (see above)
     * @return false|string Data set prepared for use in graphics
     * @throws Exception
     */
    public function buildGraphData(array $indexAssets, string $graphProvider): string
    {
        if (!in_array($graphProvider, $this->graphingProviders)) {
            throw new Exception("The specified {$graphProvider} plot provider is not supported");
        }

        if (strcasecmp($graphProvider, 'dygraph') == 0) {
            return $this->buildDygigraphData($indexAssets);
        }

        return false;
    }

    /**
     * Builds a set of raw data to use a Dygraph chart
     *
     * array[year, continentality Index]
     * @param $indexAssets array Continental index data (see above)
     * @return string Data set prepared for use in graphics
     */
    public function buildDygigraphData(array $indexAssets): string
    {
        $output = 'Date,Index\n';

        foreach ($indexAssets as $indexItem) {
            $output .= $indexItem[0].','.$indexItem[1].'\n';
        }

        return $output;
    }

    /**
     * Returns URI of directory with plotting library
     *
     * @return string URI of the directory with plotting library files
     */
    protected function getAssetUri(): string
    {
        $documentRoot = $_SERVER['DOCUMENT_ROOT'];
        $rootPath = str_replace('/', '\\', $documentRoot);

        $filePath = str_replace($rootPath, '', __DIR__);
        $srcDirectory = strstr(trim($filePath, '\\'), "src", true);
        $filePathArray = explode("\\", $srcDirectory);

        $uri = '';
        foreach ($filePathArray as $item) {
            if (strcasecmp($item, '') == 0) {
                continue;
            }
            $uri .= strtolower($item) . '/';
        }

        $uri .= 'src/assets/';

        return $uri;
    }
}
