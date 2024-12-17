<?php

namespace Controllers\Play\Minecraft;

use App\Http\Controllers\Controller;
use Domain\Play\Minecraft\Entity\DocumentEntity;

class DocumentController extends Controller
{
    public function get(int $user)
    {
        return response()->json(DocumentEntity::query()->where('user_id', $user)->get());
    }

    public function create() {
        //
    }

    public function edit() {
        //
    }

    public function delete() {
        //
    }
}
