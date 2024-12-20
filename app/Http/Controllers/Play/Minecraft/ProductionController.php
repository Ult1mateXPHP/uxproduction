<?php

namespace Controllers\Play\Minecraft;

use Application\Play\Minecraft\ProductionApi;
use Infrastructure\Root\Validator\TokenValidate;
use Illuminate\Support\Facades\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class ProductionController
{
    public function create(string $prod, string $type, string $ver, Request $request, TokenValidate $validate, ProductionApi $production) : JsonResponse {
        if($validate($request::bearerToken())) {
            return response()->json($production->createProduction($prod, $type, $ver));
        }
        return response()->json(['Error' => 'Access denied!'], 403);
    }

    public function info(string $name, ProductionApi $production) : JsonResponse {
        return response()->json($production->getProduction($name));
    }
}
