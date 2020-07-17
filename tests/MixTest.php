<?php

namespace Kaapiii\Concrete5\LaravelMix\Test;

use Kaapiii\Concrete5\LaravelMix\Mix;
use Kaapiii\Concrete5\LaravelMix\MixException;
use PHPUnit\Framework\TestCase;

/**
 * Class MixTest
 * @coversDefaultClass \Kaapiii\Concrete5\LaravelMix\Mix
 */
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
     * @covers \Kaapiii\Concrete5\LaravelMix\Mix::setAssetBasePath
     * @dataProvider assetBasePathProvider
     */
    public function testSetAssetBasePath($path, $result)
    {
        $this->mix->setAssetBasePath($path);
        $this->assertEquals($result, $this->mix->getAssetBasePath());
    }

    /**
     * @covers \Kaapiii\Concrete5\LaravelMix\Mix::getAssetBasePath
     * @dataProvider assetBasePathProvider
     */
    public function testGetAssetBasePath($path, $result)
    {
        $this->mix->setAssetBasePath($path);
        $this->assertEquals($result, $this->mix->getAssetBasePath());
    }

    public function assetBasePathProvider()
    {
        return [
            ['/some/path/', '/some/path'],
            ['/some/path', '/some/path'],
            ['test/path/', 'test/path'],
            ['test/path', 'test/path'],
        ];
    }


    public function testPrintAsset()
    {
        $this->assertTrue(true);
    }


    /**
     * Test if only the folder path is inserted into "setMixManifestPath" the file is still found.
     *
     * @covers \Kaapiii\Concrete5\LaravelMix\Mix::setMixManifestPath
     */
    public function testSetMixManifestPathWithoutManifestFile()
    {
        // Expected: /resources/mix-manifest.json
        // Provided: /resources
        $expected = '/resources/mix-manifest.json';

        $mixManifest = __DIR__ . DIRECTORY_SEPARATOR . 'resources';
        $mix = new Mix($mixManifest);

        $this->assertStringContainsString($expected, $mix->getMixManifestPath());
    }

    /**
     * Test throwing an exception if provided an invalid mix manifest path
     *
     * @covers \Kaapiii\Concrete5\LaravelMix\Mix::setMixManifestPath
     *
     * @throws MixException
     */
    public function testThrowMixExceptionInvalidMixManifestPath()
    {
        $this->expectException(MixException::class);

        $invalidMixManifestPath = __DIR__ . DIRECTORY_SEPARATOR . 'wrongPath';
        $mix = new Mix($invalidMixManifestPath);
    }


    /**
     * @covers \Kaapiii\Concrete5\LaravelMix\Mix::getMixAssets
     * @covers \Kaapiii\Concrete5\LaravelMix\Mix::parseMixManifest
     *
     * @throws MixException
     * @throws \ReflectionException
     */
    public function testGetMixAssets()
    {
        $rfClass = '\Kaapiii\Concrete5\LaravelMix\Mix';
        $rfMethod = 'getMixAssets';
        $expectedKey = '/css/main.css';


        $mixManifest = __DIR__ . DIRECTORY_SEPARATOR . 'resources';
        $mix = new Mix($mixManifest);

        // method is private -> set to public with reflection class
        $method = new \ReflectionMethod($rfClass, $rfMethod);
        $method->setAccessible(true);

        // invoke the as public declared method with the new created "mix" object
        $assets = $method->invoke($mix);

        $this->assertIsArray($assets,'Assets are not a array');
        $this->assertArrayHasKey($expectedKey, $assets, "Expected key ($expectedKey) is not present");
    }

    /**
     * Test if JsonException and MixException is thrown if an invalid mix-manifest.json
     * is provided
     *
     * @covers \Kaapiii\Concrete5\LaravelMix\Mix::getMixAssets
     * @covers \Kaapiii\Concrete5\LaravelMix\Mix::parseMixManifest
     *
     * @throws MixException
     */
    public function testMixManifestDecodingJsonError()
    {
        $this->expectException(MixException::class);

        $mixManifest = __DIR__ . DIRECTORY_SEPARATOR . 'resources/failing';
        $mix = new Mix($mixManifest);
    }

    public function testParseMixManifest()
    {
        $this->assertTrue(true);
    }

    /**
     * @covers \Kaapiii\Concrete5\LaravelMix\Mix::endsWith
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
