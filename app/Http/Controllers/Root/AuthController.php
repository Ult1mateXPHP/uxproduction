<?php

namespace Controllers\Root;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        Log::info('Register request');
        Log::info($request->collect());
        if ($request->input('username') == null) {
            return response()->json(['success' => false, 'error' => 'Имя пользователя обазательно'], 400);
        }
        if ($request->input('password') == null) {
            return response()->json(['success' => false, 'error' => 'Пароль обязателен'], 400);
        }
        User::query()->create([
            'name' => $request->input('username'),
            'password' => Hash::make($request->input('password'))
        ]);

        return response()->json(['success' => true]);
    }
}
