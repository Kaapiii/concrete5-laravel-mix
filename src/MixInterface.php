<?php

namespace Kaapiii\Concrete5\LaravelMix;

interface MixInterface
{
    public function setMixManifestPath(string $path);
    public function getMixManifestPath();
    public function printAsset(string $mixManifestPath);
}
