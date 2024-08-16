<?php

namespace App\Services;

use App\DTO\Users\StoreUserDTO;
use App\DTO\Users\UpdateUserDTO;
use App\Models\User;
use App\Models\Role;
use App\DTO\Users\GetUsersDTO;

class UsersServices
{
    public function get(GetUsersDTO $dto)
    {
        $query = User::selectFields()->with('role');

        if (isset($dto->search)) {
            $search = $dto->search;
            $query->where(function ($query) use ($search) {
                $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereRaw('LOWER(email) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereHas('role', function ($query) use ($search) {
                        $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($search) . '%']);
                    });
            });
        }

        $data = $query->orderBy('created_at', 'desc')->paginate($dto->perPage, $dto->page);

        return $data;
    }

    public function store(StoreUserDTO $dto)
    {
        $user = new User();
        $user->name = $dto->name;
        $user->email = $dto->email;
        $user->password = bcrypt($dto->password);
        $user->role_id = $dto->role_id;
        $user->save();
    }

    public function update(UpdateUserDTO $dto, $id)
    {
        $user = User::find($id);
        $user->name = $dto->name;
        $user->email = $dto->email;
        $user->password = $dto->password ? bcrypt($dto->password) : $user->password;
        $user->role_id = $dto->role_id;
        $user->save();
    }

    public function getRoles()
    {
        return Role::select('id', 'name')->get();
    }

    public function delete($id)
    {
        $user = User::find($id);
        $user->delete();
    }
}
