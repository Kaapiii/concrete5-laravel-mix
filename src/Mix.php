<?php

namespace Kaapiii\Concrete5\LaravelMix;

use Kaapiii\Concrete5\LaravelMix\MixException;
use JsonException;

final class Mix implements MixInterface
{
    /**
     * @var string
     */
    private $mixManifestKey;

    /**
     * @var string
     */
    private $mixManifestPath = '';

    /**
     * @var array
     */
    private $mixAssets = [];

    /**
     * @var string
     */
    private $assetBasePath = '';

    /**
     * Mix constructor.
     *
     * @param $mixManifestPath
     * @param string $mixManifestKey
     * @throws \Kaapiii\Concrete5\LaravelMix\MixException
     */
    public function __construct($mixManifestPath, $mixManifestKey = 'mix-manifest.json')
    {
        $this->mixManifestKey = $mixManifestKey;
        $this->setMixManifestPath($mixManifestPath);

        $this->parseMixManifest($this->getMixManifestPath());
    }

    /**
     * Set manually the base path for the laravel mix assets
     * The folder path should start from the public directory
     * Example: for a theme -> "/packages/my_theme/themes/mytheme"
     *
     * @param string $path
     */
    public function setAssetBasePath(string $path)
    {
        $this->assetBasePath = rtrim($path, DIRECTORY_SEPARATOR);
    }

    /**
     * Get the asset base path
     * (Path with which the assets from mix-manifest.json should be prefixed)
     *
     * @param string $path
     * @return string
     */
    public function getAssetBasePath()
    {
        return $this->assetBasePath;
    }

    /**
     * Get path to the mix-manifest.json
     *
     * @return string
     */
    public function getMixManifestPath()
    {
        return $this->mixManifestPath;
    }

    /**
     * Echo the laravel mix assets with cache busting (if enabled)
     * @todo move to a strategy pattern
     *
     * @param string $mixAssetPath
     * @param array $options
     */
    public function printAsset(string $mixAssetPath, $options = [])
    {
        $tag = '';
        $assets = $this->getMixAssets();

        // Check if path has a trailing slash
        $mixAssetPath = '/' . trim($mixAssetPath, '/');

        $extension = pathinfo($mixAssetPath, PATHINFO_EXTENSION);
        switch (strtolower($extension)) {
            case 'css':
                if (array_key_exists($mixAssetPath, $assets)) {
                    $assetUrl = $this->getAssetBasePath() . $assets[$mixAssetPath];
                } else {
                    // fallback to file without versioned query string
                    $assetUrl = $this->getAssetBasePath() . $mixAssetPath;
                }
                echo '<link type="text/css" rel="stylesheet" href="' . $assetUrl . '" />';
                break;
            case 'js':
                // js options
                if (is_countable($options) && $this->isScriptAttributeAllowed($options)) {
                    $tag = $options[0] . ' ';
                }
                // js
                if (array_key_exists($mixAssetPath, $assets)) {
                    $assetUrl = $this->getAssetBasePath() . $assets[$mixAssetPath];
                } else {
                    // fallback to file without versioned query string
                    $assetUrl = $this->getAssetBasePath() . $mixAssetPath;
                }
                echo '<script ' . $tag . 'src="' . $assetUrl . '"></script>';
                break;
            default:
                throw new \InvalidArgumentException('Invalid extension provided. Only js and css are supported.');
        }
    }

    /**
     * Check for allowed script attributes
     *
     * @param $options
     * @return bool
     */
    protected function isScriptAttributeAllowed($options)
    {
        $allowedTagOptions = ['defer', 'async'];
        return (bool) count(array_intersect($options, $allowedTagOptions));
    }

    /**
     * Set the path to the mix-manifest.json file
     *
     * @param string $path
     * @throws MixException
     */
    private function setMixManifestPath(string $path)
    {
        // only folder path was provided, add the mix-manifest.json to the path
        if (!$this->endsWith($path, $this->mixManifestKey)) {
            $path = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $this->mixManifestKey;
        }

        // Check if file exists
        if (!file_exists($path)) {
            throw new MixException('mix-manifest.json does not exist under the provided path: ' . $path);
        }

        $this->mixManifestPath = $path;
    }

    /**
     * Get the mix-manifest.json assets as array
     *
     * @return array
     */
    private function getMixAssets()
    {
        return $this->mixAssets;
    }

    /**
     * Parse mix-manifest.json
     *
     * @param string $mixManifestPath
     * @throws \Kaapiii\Concrete5\LaravelMix\MixException
     */
    private function parseMixManifest(string $mixManifestPath)
    {
        if (file_exists($mixManifestPath)) {
            try {
                $manifest = json_decode(file_get_contents($mixManifestPath), true, 32, JSON_THROW_ON_ERROR);

                if (is_array($manifest)) {
                    $this->mixAssets = $manifest;
                } else {
                    throw new MixException('Assets in mix-manifest.json expected to be an array. Object given instead.');
                }

            } catch (JsonException $e) {
                throw new MixException('Could not decode mix-manifest.json.', 0, $e);
            }
        }
    }

    /**
     * Check if string ends with a other sting
     *
     * @param $haystack
     * @param $needle
     * @return bool
     */
    public function endsWith ($haystack, $needle)
    {
        $length = strlen($needle);
        if ($length === 0) {
            return true;
        }
        return (substr($haystack, -$length) === $needle);
    }
}
