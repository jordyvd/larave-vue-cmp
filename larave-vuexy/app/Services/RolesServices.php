<?php

namespace App\Services;

use App\DTO\Roles\StoreRoleDTO;
use App\Models\Role;
use App\DTO\Roles\GetRoleDTO;

class RolesServices
{
    public function get(GetRoleDTO $dto)
    {
        $query = Role::select('id', 'name', 'description');

        if (isset($dto->search)) {
            $search = $dto->search;
            $query->where(function ($query) use ($search) {
                $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereRaw('LOWER(description) LIKE ?', ['%' . strtolower($search) . '%']);
            });
        }

        $data = $query->orderBy('created_at', 'desc')->paginate($dto->perPage, $dto->page);

        return $data;
    }
    public function store(StoreRoleDTO $dto)
    {
        $role = new Role();
        $role->name = $dto->name;
        $role->description = $dto->description;
        $role->save();
    }

    public function update(StoreRoleDTO $dto, $id)
    {
        $role = Role::find($id);
        $role->name = $dto->name;
        $role->description = $dto->description;
        $role->save();
    }
}
