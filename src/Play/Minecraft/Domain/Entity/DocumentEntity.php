<?php

namespace Domain\Play\Minecraft\Entity;

use Illuminate\Database\Eloquent\Model;

class DocumentEntity extends Model
{
    protected $connection = 'play';

    protected $table = 'document';

    public $timestamps = false;

    protected $fillable = [
        'type',
        'name',
        'user_id',
        'data',
    ];
}
