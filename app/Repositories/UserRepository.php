<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Auth;

class UserRepository
{
    public function getAuth()
    {
        return Auth::user();
    }
}
