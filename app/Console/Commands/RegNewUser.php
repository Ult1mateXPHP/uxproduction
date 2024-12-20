<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class RegNewUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:reg {username} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Системная регистрация пользователя [{username} {password}]';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        User::query()->create([
            'name' => $this->argument('username'),
            'password' => Hash::make($this->argument('password'))
        ]);
    }
}
