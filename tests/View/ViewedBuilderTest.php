<?php

namespace View;

use PHPUnit\Framework\TestCase;
use Soandso\ContinentalIndex\View\ViewedBuilder;

class ViewedBuilderTest extends TestCase
{
    private $viewedBuilder;

    private $indexAssets = [
        [2020, 0.78],
        [2021, 0.84],
    ];

    private $options = [];

    protected function setUp(): void
    {
        $this->viewedBuilder = new ViewedBuilder($this->indexAssets, $this->options);
    }

    protected function tearDown(): void
    {
        unset($this->viewedBuilder);
    }

    public function testSuccessGetGraphProvider()
    {
        $this->assertEquals($this->viewedBuilder->getGraphProvider(), 'dygraph');
    }

    public function testErrorGetGraphProvider()
    {
        $this->assertNotEquals($this->viewedBuilder->getGraphProvider(), 'plotter');
    }

    public function testDefaultGetGraphOptions()
    {
        $actual = '{"width":480,"height":320,"title":"Continental index","ylabel":"Index","xlabel":"Date"}';

        $this->assertEquals($this->viewedBuilder->getGraphOptions(), $actual);
    }

    public function testCustomGetGraphOptions()
    {
        $indexAssets = [
            [2020, 0.78],
            [2021, 0.84],
        ];

        $options = [
            'showRangeSelector' => true,
            'rangeSelectorHeight' => 30,
            'title' => 'Hromov Index',
        ];

        $viewedBuilder = new ViewedBuilder($indexAssets, $options);

        $actual = '{"showRangeSelector":true,"rangeSelectorHeight":30,"width":480,"height":320,"title":"Hromov Index","ylabel":"Index","xlabel":"Date"}';

        $this->assertEquals($viewedBuilder->getGraphOptions(), $actual);
    }

    public function testSuccessSetSettings()
    {
        $options = [
            'showRangeSelector' => true,
            'rangeSelectorHeight' => 30,
            'title' => 'Custom title',
            'width' => 1200,
            'height' => 800,
        ];

        $actual = '{"showRangeSelector":true,"rangeSelectorHeight":30,"width":1200,"height":800,"title":"Custom title","ylabel":"Index","xlabel":"Date"}';

        $this->viewedBuilder->setSettings($options);

        $this->assertEquals($this->viewedBuilder->getGraphOptions(), $actual);
    }

    public function testSuccessNotDefinedOptionSetSettings()
    {
        $options = [
            'showRangeSelector' => true,
            'rangeSelectorHeight' => 30,
            'title' => 'Custom title',
            'width' => 1200,
            'height' => 800,
            'customOption' => 'Custom option value',
            'customOptionTwo' => 'Custom option value Two',
        ];

        $actual = '{"showRangeSelector":true,"rangeSelectorHeight":30,"width":1200,"height":800,"title":"Custom title","ylabel":"Index","xlabel":"Date"}';

        $this->viewedBuilder->setSettings($options);

        $this->assertEquals($this->viewedBuilder->getGraphOptions(), $actual);
    }

    public function testSuccessBuildGraphData()
    {
        $indexAssets = [
            [2020, 0.78],
            [2021, 0.84],
        ];

        $provider = 'dygraph';

        $actual = 'Date,Index\n2020,0.78\n2021,0.84\n';

        $this->assertEquals($this->viewedBuilder->buildGraphData($indexAssets, $provider), $actual);
    }

    public function testNotBuildGraphData()
    {
        $indexAssets = [
            [2020, 0.78],
            [2021, 0.84],
        ];

        $provider = 'dygraph';

        $actual = 'Date,Index\n2020,0.79\n2021,0.65\n';

        $this->assertNotEquals($this->viewedBuilder->buildGraphData($indexAssets, $provider), $actual);
    }

    public function testErrorBuildGraphData()
    {
        $indexAssets = [
            [2020, 0.78],
            [2021, 0.84],
        ];

        $provider = 'plotter';

        $this->expectException(\Exception::class);
        $this->viewedBuilder->buildGraphData($indexAssets, $provider);
    }

    public function testSuccessBuildDygigraphData()
    {
        $indexAssets = [
            [2020, 0.78],
            [2021, 0.84],
        ];

        $actual = 'Date,Index\n2020,0.78\n2021,0.84\n';

        $this->assertEquals($this->viewedBuilder->buildDygigraphData($indexAssets), $actual);
    }

    public function testErrorBuildDygigraphData()
    {
        $indexAssets = [
            [2020, 0.78],
            [2021, 0.84],
        ];

        $actual = 'Date,Index\n2020,0.85\n2021,0.92\n';

        $this->assertNotEquals($this->viewedBuilder->buildDygigraphData($indexAssets), $actual);
    }
}
