<?php

namespace Controllers\Play\Minecraft;

use Application\Play\Minecraft\RconApi;
use Domain\Play\Minecraft\Entity\RconMethodEntity;
use App\Root\Infrastructure\Validator\TokenValidate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RconController
{
    /**
     * Validate Inputted Method
     * @param $method
     * @param $params
     * @param $token
     * @return array|object
     */
    private function middleware($method, $params, $token = null) : array|object {
        $entity = RconMethodEntity::query()->where('name', '=', $method);
        if($entity->count() != 1) {
            return ['Error' => 'Could not find method'];
        }
        if($entity->first()['access'] == 'protected') {
            $validate = new TokenValidate($token);
            if($validate) {
                return ['Error' => 'Access denied!'];
            }
        }
        return RconApi::$method();
    }


    /**
     * Method Input
     * @param string $method
     * @param mixed $params
     * @param Request $request
     * @return JsonResponse
     */
    public function init(string $method, mixed $params, Request $request) : JsonResponse {
        $io = $this->middleware($method, $params, $request->bearerToken());
        return response()->json($io);
    }
}
