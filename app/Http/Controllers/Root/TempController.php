<?php

namespace Controllers\Root;

use Application\Root\TempApi;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TempController
{
    public function get(string $filename, TempApi $api) : StreamedResponse|JsonResponse {
        $file = $api->get($filename);
        return response()->json($file);
    }
}
