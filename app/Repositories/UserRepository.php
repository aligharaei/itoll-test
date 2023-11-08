<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Auth;

class UserRepository
{
    public function getAuthUser()
    {
        return Auth::user();
    }
}
