<?php

namespace Kaapiii\Concrete5\LaravelMix;

interface MixInterface
{
    public function getAssetBasePath();
    public function getMixManifestPath();
    public function printAsset(string $mixManifestPath);
}
