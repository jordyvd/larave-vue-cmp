<?php

namespace App\Services;

use App\Models\User;
use App\DTO\Auth\CreateTokenAuthDTO;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AuthServices
{
    public function createToken(CreateTokenAuthDTO $dto)
    {
        $user = User::where('email', $dto->email)->first();

        if (!$user || !Hash::check($dto->password, $user->password)) {
            return null;
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return $token;
    }

    public function getUser()
    {
        $data = User::selectFields()
            ->where('id', auth()->user()->id)
            ->with('role')->first();
        return $data;
    }

    public function logout()
    {
        DB::table('personal_access_tokens')
            ->where('tokenable_id', auth()->user()->id)
            ->delete();
    }
}
