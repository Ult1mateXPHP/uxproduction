<?php

namespace Infrastructure\Play\Minecraft\Storage;

use Symfony\Component\HttpFoundation\StreamedResponse;

interface PackageInterface
{
    public static function getFile($package) : StreamedResponse|array;
}
