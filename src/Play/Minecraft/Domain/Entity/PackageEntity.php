<?php

namespace Domain\Play\Minecraft\Entity;

use Illuminate\Database\Eloquent\Model;

class PackageEntity extends Model
{
    protected $connection = 'play';

    protected $table = 'packages';

    public $timestamps = false;

    protected $fillable = [
        'prod',
        'build'
    ];
}
