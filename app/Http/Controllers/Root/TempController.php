<?php

namespace Controllers\Root;

use App\Root\Application\TempApi;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TempController
{
    public function get(string $filename, TempApi $api) : StreamedResponse|JsonResponse {
        $file = $api->get($filename);
        return response()->json($file);
    }
}
