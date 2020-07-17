<?php

namespace Kaapiii\Concrete5\LaravelMix\Test;

use Kaapiii\Concrete5\LaravelMix\Mix;
use PHPUnit\Framework\TestCase;

class MixTest extends TestCase
{

    /**
     * @var Mix
     */
    protected $mix;

    /**
     * Setup
     *
     * @throws \Kaapiii\Concrete5\LaravelMix\MixException
     */
    protected function setUp(): void
    {
        $mixManifest = __DIR__ . '/resources/mix-manifest.json';
        $this->mix = new Mix($mixManifest);
    }

    /**
     * @dataProvider setAssetBasePathProvider
     */
    public function testSetAssetBasePath($path, $result)
    {
        $this->mix->setAssetBasePath($path);
        $cleaned = $this->mix->getAssetBasePath();

        $this->assertEquals($result, $cleaned);
    }

    public function setAssetBasePathProvider()
    {
        return [
            ['/some/path/', '/some/path'],
            ['/some/path', '/some/path'],
            ['test/path/', 'test/path'],
            ['test/path', 'test/path'],
        ];
    }

    public function getAssetBasePath()
    {
        $this->assertTrue(true);
    }

    public function testPrintAsset()
    {
        $this->assertTrue(true);
    }

    public function testSetMixManifestPath()
    {
        // method is private -> set to public with reflection class

        /*$method = new ReflectionMethod(
        //Class , Method
            'Foo', 'doSomethingPrivate'
        );*/

        //$method->setAccessible(TRUE);
        $this->assertTrue(true);
    }

    public function testGetMixAssets()
    {
        $this->assertTrue(true);
    }

    public function testParseMixManifest()
    {
        $this->assertTrue(true);
    }

    /**
     * @dataProvider endsWithProvider
     */
    public function testEndsWith($string, $endsWith, $result)
    {
        $this->assertEquals($result, $this->mix->endsWith($string, $endsWith));
    }

    public function endsWithProvider()
    {
        return [
            // string, endsWith, assertion
            ['/css/main.css', 'css' , true],
            ['/css/main.js', 'js' , true],
            ['/css/main.js', 'css' , false],
            ['/css/vendor.css', 'js' , false],
        ];
    }
}
