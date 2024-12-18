<?php

namespace Domain\Play\Minecraft\Entity;

use Illuminate\Database\Eloquent\Model;

class RconMethodEntity extends Model
{
    protected $table = 'rcon_methods';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'access'
    ];
}
