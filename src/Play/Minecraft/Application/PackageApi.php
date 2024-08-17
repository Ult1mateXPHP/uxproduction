<?php

namespace Application\Play\Minecraft;

use Domain\Play\Minecraft\Entity\PackageEntity;
use Infrastructure\Play\Minecraft\Storage\PackageDriver;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PackageApi
{
    /**
     * Search Package Entity
     * @param $prod
     * @param $build
     * @return object
     */
    private function get($prod, $build) : object {
        $package = PackageEntity::query()->where('prod', '=', $prod)->where('build', '=', $build);
        return $package;
    }

    /**
     * Realize New Package Entity
     * @param $prod
     * @param $build
     * @return object
     */
    private function create($prod, $build) : object {
        $package = new PackageEntity();
        $package->prod = $prod;
        $package->build = $build;
        return $package;
    }

    /**
     * Getting Package Process
     * @param $prod
     * @param $build
     * @param ProductionApi $productionApi
     * @return StreamedResponse|array
     */
    public function getPackage($prod, $build, ProductionApi $productionApi) : StreamedResponse|array {
        $prod_id = ($productionApi->getProductionId($prod))->id;
        if($build == 'latest') {
            $build = $this->latestBuild($prod_id);
        }
        $package = $this->get($prod_id, $build);
        if($package->count() == 1) {
            $filename = $prod.'-'.$build.'.zip';
            $file = PackageDriver::getFile($filename);
            return $file;
        }
        return ['Error' => 'File doesnt exists'];
    }

    /**
     * Getting latest build by prod
     * @param $prod_id
     * @return int
     */
    public function latestBuild($prod_id) : int {
        $entity = new PackageEntity();
        $result = $entity
            ->newQuery()
            ->where('prod', '=', $prod_id)
            ->orderByDesc('build')
            ->limit(1)
            ->first();
        return $result->build;
    }

    /**
     * Create New Package Process
     * @param $prod
     * @param $build
     * @return string[]
     */
    public function createPackage($prod, $build) : array {
        if($this->get($prod, $build) == 1) {
            return ['Error' => 'This package already exists'];
        }
        $package = $this->create($prod, $build);
        if($package->save()) {
            return ['Status' => 'Success'];
        }
        return ['Error' => 'Error during save new entity'];
    }
}
