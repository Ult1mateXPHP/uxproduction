<?php

namespace Application\Play\Minecraft;

use Domain\Play\Minecraft\Entity\ProductionEntity;

class ProductionApi
{
    /**
     * Searching Game Production Entity
     * @param $name
     * @return object
     */
    private function get($name) : object {
        return ProductionEntity::query()->where('name', '=', $name);
    }

    /**
     * Create New Game Production Entity Proccess
     * @param $prod
     * @param $type
     * @param $ver
     * @return bool
     */
    private function create($prod, $type, $ver) : bool {
        $newProd = new ProductionEntity();
        $newProd->name = $prod;
        $newProd->type = $type;
        $newProd->ver = $ver;
        return $newProd->save();
    }

    /**
     * Getting (by name) Production Entity Proccess
     * @param $name
     * @return string[]
     */
    public function getProduction($name) : array {
        $entity = $this->get($name);
        if($entity->count() == 1) {
            return $entity->get();
        }
        return ['Error' => 'Production doesnt exists'];
    }

    /**
     * Getting (by id) Production Entity Proccess
     * @param $name
     * @return mixed|null
     */
    public function getProductionId($name) {
        return ProductionEntity::query()->where('name', '=', $name)->first('id');
    }

    /**
     * Creating New Production Entitiy Proccess
     * @return string[]
     */
    public function createProduction($name, $type, $ver) : array {
        $newProdCreated = $this->create($name, $type, $ver);
        if($newProdCreated->save()) {
            return ['Status' => 'Success'];
        } else {
            return ['Error' => 'Error during new entity save process'];
        }
    }
}
