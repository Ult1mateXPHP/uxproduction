<?php

namespace Domain\Play\Minecraft\Entity;

use Illuminate\Database\Eloquent\Model;

class ProductionEntity extends Model
{
    protected $connection = 'play';

    protected $table = 'prods';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'type',
        'ver'
    ];
}
