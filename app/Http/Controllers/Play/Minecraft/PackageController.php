<?php

namespace Controllers\Play\Minecraft;

use Application\Play\Minecraft\PackageApi;
use Infrastructure\Root\Validator\TokenValidate;
use Application\Play\Minecraft\ProductionApi;
use Illuminate\Support\Facades\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PackageController
{
    public function create($prod, $build, Request $request, TokenValidate $validate, PackageApi $package) : JsonResponse {
        if($validate($request::bearerToken())) {
            return response()->json($package->createPackage($prod, $build));
        }
        return response()->json(['Error' => 'Access denied!'], 403);
    }
    public function download(string $prod, string $build, PackageApi $package, ProductionApi $productionApi) : JsonResponse|StreamedResponse {
        $response = $package->getPackage($prod, $build, $productionApi);
        if(is_array($response)) {
            return response()->json($response);
        } else {
            return $response;
        }
    }
}
