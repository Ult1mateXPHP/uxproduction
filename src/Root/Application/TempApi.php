<?php

namespace Application\Root;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TempApi
{
    public function get($filename) : StreamedResponse|array {
        $fileExists = Storage::drive('temp')->exists($filename);
        if($fileExists) {
            return Storage::download('public/temp/'.$filename);
        }
        return ['Error' => 'File doesnt exists'];
    }
}
