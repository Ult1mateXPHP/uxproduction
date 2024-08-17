<?php

namespace Infrastructure\Play\Minecraft\Storage;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PackageDriver implements PackageInterface
{
    /**
     * Return file downloading
     * @param $package
     * @return StreamedResponse
     */
    private static function get($package) : StreamedResponse {
        return Storage::download('public/package/'.$package);
    }

    /**
     * Get File from storage
     * @param $package
     * @return StreamedResponse|array
     */
    public static function getFile($package) : StreamedResponse|array {
        if(Storage::drive('package')->exists($package)) {
            return self::get($package);
        }
        return ['Error' => 'File doesnt exists'];
    }
}
